<?php

namespace Sysborg\FocusNfe\tests\Traits;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;
use Sysborg\FocusNfe\app\Rules\CnaeRule;
use Sysborg\FocusNfe\app\Rules\CepRule;
use Sysborg\FocusNfe\app\Rules\CnpjRule;

trait BootstrapsFacadesTrait
{
    protected function bootstrapFacades(): void
    {
        $container = new Container();

        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => [
                    'production' => 'https://api.focusnfe.com.br',
                ],
            ],
        ]));

        $translator = new Translator(new ArrayLoader(), 'pt_BR');
        $validatorFactory = new ValidationFactory($translator, $container);

        $container->instance('translator', $translator);
        $container->instance('validator', $validatorFactory);
        $container->instance('http', new HttpFactory());
        $container->instance('events', new Dispatcher($container));
        $container->instance('log', new class {
            public function error(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });

        Container::setInstance($container);
        Facade::setFacadeApplication($container);

        Validator::extend('cnpj', fn ($attribute, $value) => (new CnpjRule())->passes($attribute, (string) $value), (new CnpjRule())->message());
        Validator::extend('cep', fn ($attribute, $value) => (new CepRule())->passes($attribute, (string) $value), (new CepRule())->message());
        Validator::extend('cnae', fn ($attribute, $value) => (new CnaeRule())->passes($attribute, (string) $value), (new CnaeRule())->message());
    }
}

