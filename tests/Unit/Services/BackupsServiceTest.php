<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\Backups;

class BackupsServiceTest extends TestCase
{
    private Backups $service;
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

        $this->service = new Backups($this->token, $this->ambiente);
    }

    public function test_consulta_backup_com_sucesso(): void
    {
        $cnpj = '11222333000181';
        $url = config('focusnfe.URL.production') . sprintf(Backups::URL, $cnpj);

        Http::fake([
            $url => Http::response(['cnpj' => $cnpj, 'status' => 'ok'], 200),
        ]);

        $response = $this->service->get($cnpj);

        $this->assertTrue($response->ok());
    }

    public function test_retorna_erro_quando_cnpj_nao_encontrado(): void
    {
        $cnpj = '00000000000000';
        $url = config('focusnfe.URL.production') . sprintf(Backups::URL, $cnpj);

        Http::fake([
            $url => Http::response(['codigo' => 'nao_encontrado', 'mensagem' => 'CNPJ não encontrado'], 404),
        ]);

        $response = $this->service->get($cnpj);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
    }

    public function test_envia_header_authorization_basic(): void
    {
        $cnpj = '11222333000181';
        $url = config('focusnfe.URL.production') . sprintf(Backups::URL, $cnpj);

        Http::fake([
            $url => Http::response(['cnpj' => $cnpj], 200),
        ]);

        $this->service->get($cnpj);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Basic ' . base64_encode($this->token));
        });
    }

    public function test_monta_url_consulta_corretamente(): void
    {
        $cnpj = '11222333000181';
        $expectedUrl = config('focusnfe.URL.production') . sprintf(Backups::URL, $cnpj);

        Http::fake([
            $expectedUrl => Http::response(['cnpj' => $cnpj], 200),
        ]);

        $this->service->get($cnpj);

        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });
    }
}
