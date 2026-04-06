<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\Municipios;

class MunicipiosServiceTest extends TestCase
{
    private Municipios $service;
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

        $this->service = new Municipios($this->token, $this->ambiente);
    }

    public function test_lista_municipios_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response([['codigo' => '4106902', 'descricao' => 'Curitiba']], 200),
        ]);

        $response = $this->service->list();

        $this->assertTrue($response->ok());
    }

    public function test_get_municipio_com_sucesso(): void
    {
        $codigo = '4106902';
        $url = config('focusnfe.URL.production') . Municipios::URL . "/$codigo";

        Http::fake([
            $url => Http::response(['codigo' => $codigo, 'descricao' => 'Curitiba'], 200),
        ]);

        $response = $this->service->get($codigo);

        $this->assertTrue($response->ok());
        $this->assertSame($codigo, $response->json('codigo'));
    }

    public function test_get_municipio_nao_encontrado(): void
    {
        $codigo = '0000000';
        $url = config('focusnfe.URL.production') . Municipios::URL . "/$codigo";

        Http::fake([
            $url => Http::response(['codigo' => 'nao_encontrado', 'mensagem' => 'Município não encontrado'], 404),
        ]);

        $response = $this->service->get($codigo);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
    }

    public function test_get_lista_servico_com_sucesso(): void
    {
        $codigoMunicipio = '4106902';
        $codigoServico = '01.01';
        $url = config('focusnfe.URL.production') . Municipios::URL . "/$codigoMunicipio/itens_lista_servico/$codigoServico";

        Http::fake([
            $url => Http::response(['codigo_municipio' => $codigoMunicipio, 'codigo_servico' => $codigoServico], 200),
        ]);

        $response = $this->service->getListaServico($codigoMunicipio, $codigoServico);

        $this->assertTrue($response->ok());
    }

    public function test_get_codigos_tributarios_com_sucesso(): void
    {
        $codigoMunicipio = '4106902';
        $codigoTributario = '001';
        $url = config('focusnfe.URL.production') . Municipios::URL . "/$codigoMunicipio/codigos_tributarios_municipio/$codigoTributario";

        Http::fake([
            $url => Http::response(['codigo_municipio' => $codigoMunicipio, 'codigo_tributario' => $codigoTributario], 200),
        ]);

        $response = $this->service->getCodigosTributarios($codigoMunicipio, $codigoTributario);

        $this->assertTrue($response->ok());
    }
}
