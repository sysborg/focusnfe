<?php

namespace Sysborg\FocusNfe\app\Providers;

use InvalidArgumentException;
use Illuminate\Support\ServiceProvider;
use Sysborg\FocusNfe\app\Services\FocusNfeManager;
use Sysborg\FocusNfe\app\Services\{
    Backups,
    CEP,
    CFOP,
    CNAE,
    Cnpjs,
    ConsultaEmails,
    CTe,
    CTERecebidas,
    Empresas,
    MDFe,
    Municipios,
    NCM,
    NFCe,
    NFe,
    NFeRecebidas,
    NFSe,
    NFSeArquivo,
    NFSeNacional,
    NFSeRecebidas,
    Webhooks
};
use Illuminate\Support\Facades\Validator;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Sysborg\FocusNfe\app\Rules\{CepRule, CnaeRule, CnpjRule};
use Illuminate\Support\Facades\Event;

/**
 * Service provider responsável por registrar e inicializar os serviços do FocusNFe
 */
class SBFocusNFeProvider extends ServiceProvider
{
    /**
     * Serviços do FocusNFe registrados como singleton.
     *
     * @var array<int, class-string>
     */
    private const FOCUSNFE_SERVICES = [
        Backups::class,
        CEP::class,
        CFOP::class,
        CNAE::class,
        Cnpjs::class,
        ConsultaEmails::class,
        CTe::class,
        CTERecebidas::class,
        Empresas::class,
        MDFe::class,
        Municipios::class,
        NCM::class,
        NFCe::class,
        NFe::class,
        NFeRecebidas::class,
        NFSe::class,
        NFSeArquivo::class,
        NFSeNacional::class,
        NFSeRecebidas::class,
        Webhooks::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/focusnfe.php', 'focusnfe');
        $this->validateConfiguration();

        foreach (self::FOCUSNFE_SERVICES as $serviceClass) {
            $this->registerFocusNfeService($serviceClass);
        }

        $this->app->singleton('focusnfe', static function ($app) {
            return new FocusNfeManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/focusnfe.php' => config_path('focusnfe.php'),
        ], 'config');

        Validator::extend('cnpj', function ($attribute, $value, $parameters, $validator) {
            return (new CnpjRule())->passes($attribute, $value);
        }, (new CnpjRule())->message());

        Validator::extend('cep', function ($attribute, $value, $parameters, $validator) {
            return (new CepRule())->passes($attribute, $value);
        }, (new CepRule())->message());

        Validator::extend('cnae', function ($attribute, $value, $parameters, $validator) {
            return (new CnaeRule())->passes($attribute, $value);
        }, (new CnaeRule())->message());

        foreach (config('focusnfe.listeners.hooks', []) as $listener) {
            Event::listen(HooksReceived::class, $listener);
        }
    }

    /**
     * Registra um service do FocusNFe injetando os valores vindos da configuração.
     *
     * @param class-string $serviceClass
     * @return void
     */
    private function registerFocusNfeService(string $serviceClass): void
    {
        $this->app->singleton($serviceClass, static function () use ($serviceClass) {
            return new $serviceClass(
                (string) config('focusnfe.token'),
                (string) config('focusnfe.ambiente')
            );
        });
    }

    /**
     * Valida a configuração essencial do pacote para falhar cedo.
     *
     * @throws InvalidArgumentException
     * @return void
     */
    private function validateConfiguration(): void
    {
        $token = trim((string) config('focusnfe.token'));
        $ambiente = (string) config('focusnfe.ambiente', 'production');
        $urls = (array) config('focusnfe.URL', []);

        if ($token === '') {
            throw new InvalidArgumentException('A configuração focusnfe.token é obrigatória.');
        }

        if (!array_key_exists($ambiente, $urls)) {
            $allowed = implode(', ', array_keys($urls));

            throw new InvalidArgumentException(
                "Ambiente FocusNFe inválido [{$ambiente}]. Use um dos ambientes configurados: {$allowed}."
            );
        }

        if (!is_string($urls[$ambiente]) || trim($urls[$ambiente]) === '') {
            throw new InvalidArgumentException(
                "A URL base do ambiente FocusNFe [{$ambiente}] precisa estar configurada."
            );
        }
    }
}
