<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\ConsultaEmails;

class ConsultaEmailsServiceTest extends TestCase
{
    private ConsultaEmails $service;
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => 'https://api.focusnfe.com.br'],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
            ],
        ]));
        $container->instance('http', new HttpFactory());
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static
            {
                return $this;
            }

            public function error(string $message, array $context = []): void
            {
            }

            public function debug(string $message, array $context = []): void
            {
            }

            public function info(string $message, array $context = []): void
            {
            }

            public function warning(string $message, array $context = []): void
            {
            }
        });

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        if (!class_exists('Log')) {
            class_alias(LogFacade::class, 'Log');
        }

        $this->service = new ConsultaEmails($this->token, $this->ambiente);
    }

    public function test_consulta_email_com_sucesso(): void
    {
        $email = 'test@example.com';
        $url = config('focusnfe.URL.production') . ConsultaEmails::URL . "/$email";

        Http::fake([
            $url => Http::response(['email' => 'test@example.com', 'status' => 'blocked'], 200),
        ]);

        $response = $this->service->get($email);

        $this->assertTrue($response->ok());
        $this->assertSame('test@example.com', $response->json('email'));
        $this->assertSame('blocked', $response->json('status'));
    }

    public function test_consulta_email_nao_encontrado(): void
    {
        $email = 'notfound@example.com';
        $url = config('focusnfe.URL.production') . ConsultaEmails::URL . "/$email";

        Http::fake([
            $url => Http::response(['codigo' => 'nao_encontrado', 'mensagem' => 'E-mail não encontrado'], 404),
        ]);

        $response = $this->service->get($email);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
    }

    public function test_deleta_email_com_sucesso(): void
    {
        $email = 'test@example.com';
        $url = config('focusnfe.URL.production') . ConsultaEmails::URL . "/$email";

        Http::fake([
            $url => Http::response(['mensagem' => 'E-mail removido com sucesso'], 200),
        ]);

        $response = $this->service->delete($email);

        $this->assertTrue($response->ok());
    }

    public function test_deleta_email_nao_encontrado(): void
    {
        $email = 'notfound@example.com';
        $url = config('focusnfe.URL.production') . ConsultaEmails::URL . "/$email";

        Http::fake([
            $url => Http::response(['codigo' => 'nao_encontrado', 'mensagem' => 'E-mail não encontrado'], 404),
        ]);

        $response = $this->service->delete($email);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
    }
}
