<?php

namespace Sysborg\FocusNFe\app\Providers;

use Illuminate\Support\ServiceProvider;
use Sysborg\FocusNFe\App\Services\CFOP;
use Sysborg\FocusNFe\App\Services\NFSe;
use Sysborg\FocusNFe\App\Rules\CnpjRule;
use Illuminate\Support\Facades\Validator;
use Sysborg\FocusNFe\App\Services\Empresas;
use Sysborg\FocusNFe\App\Services\NFSeNacional;

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
        $this->loadRoutesFrom(__DIR__.'/../../routes/sbfocusnfe.php');

        $this->publishes([
            __DIR__ . '/../../config/focusnfe.php' => config_path('focusnfe.php'),
        ], 'config');

        Validator::extend('cnpj', function ($attribute, $value, $parameters, $validator) {
            return (new CnpjRule())->passes($attribute, $value);
        }, (new CnpjRule())->message());

        $this->app->singleton('focusnfe.empresa', function ($app) {
            return new Empresas(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfse', function ($app) {
            return new NFSe(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.nfse_nacional', function ($app) {
            return new NFSeNacional(config('focusnfe.token'));
        });

        $this->app->singleton('focusnfe.CFOP', function ($app) {
            return new CFOP(config('focusnfe.token'));
        });
        
    }
}
