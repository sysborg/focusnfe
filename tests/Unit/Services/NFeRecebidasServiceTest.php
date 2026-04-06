<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeRecebidasDTO;
use Sysborg\FocusNfe\app\Services\NFeRecebidas;

class NFeRecebidasServiceTest extends TestCase
{
    private NFeRecebidas $service;
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

        $this->service = new NFeRecebidas($this->token, $this->ambiente);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_lista_nfes_recebidas_por_cnpj_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['data' => []], 200),
        ]);

        $response = $this->service->listByCnpj('11222333000181');

        $this->assertTrue($response->ok());
    }

    public function test_manifestar_nfe_com_sucesso(): void
    {
        $chave = 'chave-nfe-teste';

        Http::fake([
            '*' => Http::response(['status' => 'registrado'], 200),
        ]);

        $dto = new NFeRecebidasDTO('210', 'Mercadoria recebida');
        $response = $this->service->manifestar($chave, $dto);

        $this->assertTrue($response->ok());
        $this->assertSame('registrado', $response->json('status'));
    }

    public function test_consultar_manifesto_com_sucesso(): void
    {
        $chave = 'chave-nfe-teste';

        Http::fake([
            $this->baseUrl . NFeRecebidas::URL . '/' . $chave . '/manifesto' => Http::response(['status' => 'registrado'], 200),
        ]);

        $response = $this->service->consultarManifesto($chave);

        $this->assertTrue($response->ok());
    }

    public function test_retorna_erro_em_falha(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Unprocessable Entity'], 422),
        ]);

        $response = $this->service->listByCnpj('11222333000181');

        $this->assertTrue($response->failed());
    }
}
