<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\NFSeRecebidas;

class NFSeRecebidasServiceTest extends TestCase
{
    private NFSeRecebidas $service;
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

        $this->service = new NFSeRecebidas($this->token, $this->ambiente);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_lista_nfses_recebidas_por_cnpj(): void
    {
        Http::fake([
            '*' => Http::response(['data' => []], 200),
        ]);

        $response = $this->service->listByCnpj('11222333000181');

        $this->assertTrue($response->ok());
    }

    public function test_get_nfse_recebida_por_chave(): void
    {
        $chave = 'chave-nfse-teste';

        Http::fake([
            $this->baseUrl . NFSeRecebidas::URL . '/' . $chave => Http::response(['chave' => $chave], 200),
        ]);

        $response = $this->service->getByChave($chave);

        $this->assertTrue($response->ok());
    }

    public function test_retorna_erro_quando_nao_encontrado(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $response = $this->service->getByChave('chave-inexistente');

        $this->assertTrue($response->failed());
    }
}
