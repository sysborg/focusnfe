<?php

namespace Sysborg\FocusNFe\app\Providers;

use Illuminate\Support\ServiceProvider;
use Sysborg\FocusNFe\app\Services\CFOP;
use Sysborg\FocusNFe\app\Services\NFSe;
use Sysborg\FocusNFe\app\Rules\CnpjRule;
use Illuminate\Support\Facades\Validator;
use Sysborg\FocusNFe\app\Services\Empresas;
use Sysborg\FocusNFe\app\Services\NFSeNacional;

class SBFocusNFeProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
