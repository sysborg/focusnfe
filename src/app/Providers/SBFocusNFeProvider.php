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
use Illuminate\Support\Facades\Validator;
use Sysborg\FocusNFe\app\Events\HooksReceived;
use Sysborg\FocusNFe\app\Rules\{CepRule, CnaeRule, CnpjRule};
use Illuminate\Support\Facades\Event;

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

        $this->app->singleton(Backups::class, function ($app) {
            return new Backups(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CEP::class, function ($app) {
            return new CEP(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CFOP::class, function ($app) {
            return new CFOP(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CNAE::class, function ($app) {
            return new CNAE(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CNPJs::class, function ($app) {
            return new CNPJs(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(ConsultaEmails::class, function ($app) {
            return new ConsultaEmails(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CTe::class, function ($app) {
            return new CTe(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(CTeRecebidas::class, function ($app) {
            return new CTeRecebidas(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(Empresas::class, function ($app) {
            return new Empresas(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(MDFe::class, function ($app) {
            return new MDFe(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(Municipios::class, function ($app) {
            return new Municipios(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NCM::class, function ($app) {
            return new NCM(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFCe::class, function ($app) {
            return new NFCe(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFe::class, function ($app) {
            return new NFe(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFeRecebidas::class, function ($app) {
            return new NFeRecebidas(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFSe::class, function ($app) {
            return new NFSe(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFSeArquivos::class, function ($app) {
            return new NFSeArquivos(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFSeNacional::class, function ($app) {
            return new NFSeNacional(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        $this->app->singleton(NFSeRecebidas::class, function ($app) {
            return new NFSeRecebidas(config('focusnfe.token'), config('focusnfe.ambiente'));
        });

        Event::listen(HooksReceived::class, [
            ...config('focusnfe.listeners.hooks', [])
        ]);
    }
}
