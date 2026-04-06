<?php

namespace {
    if (!function_exists('config_path')) {
        function config_path(string $path = ''): string
        {
            return $path;
        }
    }
}

namespace Sysborg\FocusNfe\tests\Unit\Providers {

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Sysborg\FocusNfe\app\Providers\SBFocusNFeProvider;

class SBFocusNFeProviderTest extends TestCase
{
    public function test_provider_registra_listeners_configurados_para_hooks_received(): void
    {
        $container = new class () extends Container {
            public function configPath($path = ''): string
            {
                return (string) $path;
            }
        };
        $captured = [];

        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => 'https://api.focusnfe.com.br'],
                'token' => 'test-token',
                'ambiente' => 'production',
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'listeners' => [
                    'hooks' => [
                        static function (HooksReceived $event) use (&$captured): void {
                            $captured[] = $event->data;
                        },
                    ],
                ],
            ],
        ]));

        $translator = new Translator(new ArrayLoader(), 'pt_BR');
        $validatorFactory = new ValidationFactory($translator, $container);

        $container->instance('translator', $translator);
        $container->instance('validator', $validatorFactory);
        $container->instance('http', new HttpFactory());
        $container->instance('events', new Dispatcher($container));
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        $provider = new SBFocusNFeProvider($container);
        $provider->register();
        $provider->boot();

        Event::dispatch(new HooksReceived(['evento' => 'teste'], 'https://exemplo.test/webhook'));

        $this->assertCount(1, $captured);
        $this->assertSame('teste', $captured[0]['evento']);
        $this->assertTrue(Validator::make(['cnpj' => '11222333000181'], ['cnpj' => 'cnpj'])->passes());
    }
}
}
