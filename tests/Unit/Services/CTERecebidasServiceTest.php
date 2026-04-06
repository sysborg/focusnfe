<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CTERecebidasDTO;
use Sysborg\FocusNfe\app\Services\CTERecebidas;

class CTERecebidasServiceTest extends TestCase
{
    private CTERecebidas $service;
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

        $this->service = new CTERecebidas($this->token, $this->ambiente);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_consulta_ctes_recebidas_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['data' => []], 200),
        ]);

        $response = $this->service->consulta('11222333000181');

        $this->assertTrue($response->ok());
    }

    public function test_consulta_cte_individual_com_sucesso(): void
    {
        $chave = 'chave-teste';

        Http::fake([
            $this->baseUrl . CTERecebidas::URL . '/' . $chave => Http::response(['chave' => $chave], 200),
        ]);

        $response = $this->service->consultaCTE($chave);

        $this->assertTrue($response->ok());
    }

    public function test_informa_desacordo_com_sucesso(): void
    {
        $chave = 'chave-teste';

        Http::fake([
            $this->baseUrl . CTERecebidas::URL . '/' . $chave . '/desacordo' => Http::response(['status' => 'registrado'], 200),
        ]);

        $dto = new CTERecebidasDTO('Observacao');
        $response = $this->service->informarDesacordo($chave, $dto);

        $this->assertTrue($response->ok());
    }

    public function test_consulta_desacordo_com_sucesso(): void
    {
        $chave = 'chave-teste';

        Http::fake([
            $this->baseUrl . CTERecebidas::URL . '/' . $chave . '/desacordo' => Http::response(['status' => 'registrado'], 200),
        ]);

        $response = $this->service->consultaDesacordo($chave);

        $this->assertTrue($response->ok());
    }

    public function test_retorna_erro_quando_falha(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Unprocessable Entity'], 422),
        ]);

        $response = $this->service->consulta('11222333000181');

        $this->assertTrue($response->failed());
    }
}
