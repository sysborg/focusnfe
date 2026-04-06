<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\FocusNfeLogger;

class FocusNfeLoggerTest extends TestCase
{
    private object $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = new class () {
            public array $entries = [];

            public function channel(?string $channel = null): static
            {
                $this->entries[] = ['method' => 'channel', 'channel' => $channel];

                return $this;
            }

            public function debug(string $message, array $context = []): void
            {
                $this->entries[] = ['method' => 'debug', 'message' => $message, 'context' => $context];
            }

            public function info(string $message, array $context = []): void
            {
                $this->entries[] = ['method' => 'info', 'message' => $message, 'context' => $context];
            }

            public function warning(string $message, array $context = []): void
            {
                $this->entries[] = ['method' => 'warning', 'message' => $message, 'context' => $context];
            }

            public function error(string $message, array $context = []): void
            {
                $this->entries[] = ['method' => 'error', 'message' => $message, 'context' => $context];
            }
        };

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'log' => ['channel' => 'focusnfe', 'level' => 'warning'],
            ],
        ]));
        $container->instance('log', $this->logger);

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);
    }

    public function test_sanitize_mascara_dados_sensiveis_recursivamente(): void
    {
        $sanitized = FocusNfeLogger::sanitize([
            'token' => 'segredo',
            'payload' => [
                'authorization' => 'Bearer 123',
                'cpf' => '12345678909',
                'livre' => 'valor',
            ],
        ]);

        $this->assertSame('***REDACTED***', $sanitized['token']);
        $this->assertSame('***REDACTED***', $sanitized['payload']['authorization']);
        $this->assertSame('***REDACTED***', $sanitized['payload']['cpf']);
        $this->assertSame('valor', $sanitized['payload']['livre']);
    }

    public function test_respeita_nivel_configurado_e_canal_de_log(): void
    {
        FocusNfeLogger::debug('debug ignorado', ['token' => '123']);
        FocusNfeLogger::info('info ignorado', ['token' => '123']);
        FocusNfeLogger::warning('warning registrado', ['cnpj' => '07504505000132']);
        FocusNfeLogger::error('error registrado', ['authorization' => 'Bearer abc']);

        $methods = array_column($this->logger->entries, 'method');

        $this->assertSame(['channel', 'warning', 'channel', 'error'], $methods);
        $this->assertSame('***REDACTED***', $this->logger->entries[1]['context']['cnpj']);
        $this->assertSame('***REDACTED***', $this->logger->entries[3]['context']['authorization']);
    }
}
