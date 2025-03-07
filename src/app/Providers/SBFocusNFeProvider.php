<?php

namespace Sysborg\FocusNFe\app\Providers;

use Illuminate\Support\ServiceProvider;
use Sysborg\FocusNFe\app\Services\{
    Backups,
    CEP,
    CFOP,
    CNAE,
    CNPJs,
    ConsultaEmails,
    CTe,
    CTeRecebidas,
    Empresas,
    MDFe,
    Municipios,
    NCM,
    NFCe,
    NFe,
    NFeRecebidas,
    NFSe,
    NFSeArquivos,
    NFSeNacional,
    NFSeRecebidas
};
use Sysborg\FocusNFe\app\Services\NFSe;
use Illuminate\Support\Facades\Validator;
use Sysborg\FocusNFe\app\Events\HooksReceived;
use Sysborg\FocusNFe\app\Rules\{CepRule, CnaeRule, CnpjRule};

class SBFocusNFeProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/focusnfe.php', 'focusnfe');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->router->group([
            'prefix' => config('focusnfe.apiPrefix') . '/sbfocus',
            'middleware' => config('focusnfe.middlewares')
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/sbfocusnfe.php');
        });

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

        $this->app->singleton('focusnfe.Backups', function ($app) {
            return new Backups(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.CEP', function ($app) {
            return new CEP(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.CFOP', function ($app) {
            return new CFOP(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.cnae', function ($app) {
            return new CNAE(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.cnpjs', function ($app) {
            return new CNPJs(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.consultaEmails', function ($app) {
            return new ConsultaEmails(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.cte', function ($app) {
            return new CTe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.cteRecebidas', function ($app) {
            return new CTeRecebidas(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.empresas', function ($app) {
            return new Empresas(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.mdfe', function ($app) {
            return new MDFe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.municipios', function ($app) {
            return new Municipios(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.ncm', function ($app) {
            return new NCM(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfce', function ($app) {
            return new NFCe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfe', function ($app) {
            return new NFe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfeRecebidas', function ($app) {
            return new NFeRecebidas(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfse', function ($app) {
            return new NFSe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfseArquivos', function ($app) {
            return new NFSeArquivos(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfse_nacional', function ($app) {
            return new NFSeNacional(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfseRecebidas', function ($app) {
            return new NFSeRecebidas(config('focusnfe.token'));
        });

        Event::listen(HooksReceived::class, [
            ...config('focusnfe.listeners.hooks', [])
        ]);
    }
}
