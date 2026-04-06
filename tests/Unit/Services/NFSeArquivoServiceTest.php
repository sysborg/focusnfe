<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\NFSeArquivo;

class NFSeArquivoServiceTest extends TestCase
{
    private NFSeArquivo $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => $this->baseUrl],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
            ],
        ]));
        $container->instance('http', new HttpFactory());
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
        });

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        $this->service = new NFSeArquivo($this->token, $this->ambiente);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_get_lote_rps_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['referencia' => 'REF001', 'status' => 'processado'], 200),
        ]);

        $response = $this->service->get('REF001');

        $this->assertTrue($response->ok());
        $this->assertSame('REF001', $response->json('referencia'));
        $this->assertSame('processado', $response->json('status'));
    }

    public function test_get_lote_rps_nao_encontrado(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $response = $this->service->get('REF-INEXISTENTE');

        $this->assertTrue($response->failed());
    }

    public function test_monta_url_get_corretamente(): void
    {
        Http::fake([
            '*' => Http::response(['referencia' => 'REF001', 'status' => 'processado'], 200),
        ]);

        $this->service->get('REF001');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/v2/lotes_rps/REF001');
        });
    }
}
