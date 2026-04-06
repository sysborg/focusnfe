<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\NCM;
use Sysborg\FocusNfe\tests\mocks\Stub\NCMStub;

class NCMServiceTest extends TestCase
{
    private NCM $service;
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

        $this->service = new NCM($this->token, $this->ambiente);
    }

    public function test_lista_ncms_com_sucesso(): void
    {
        $url = config('focusnfe.URL.production') . NCM::URL . '?offset=1&codigo=&descricao=';

        Http::fake([
            $url => Http::response(NCMStub::consultaNCM(), 200),
        ]);

        $response = $this->service->list();

        $this->assertTrue($response->ok());
        $this->assertIsArray($response->json());
        $this->assertArrayHasKey('codigo', $response->json()[0]);
    }

    public function test_lista_ncms_com_filtros_monta_url_corretamente(): void
    {
        $offset = 2;
        $codigo = '90049090';
        $descricao = 'oculos';
        $url = config('focusnfe.URL.production') . NCM::URL . "?offset={$offset}&codigo={$codigo}&descricao={$descricao}";

        Http::fake([
            $url => Http::response(NCMStub::consultaNCM(), 200),
        ]);

        $this->service->list($offset, $codigo, $descricao);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url;
        });
    }

    public function test_consulta_ncm_por_codigo_com_sucesso(): void
    {
        $codigo = '90049090';
        $url = config('focusnfe.URL.production') . NCM::URL . "/{$codigo}";

        Http::fake([
            $url => Http::response(NCMStub::consultaNCM()[0], 200),
        ]);

        $response = $this->service->get($codigo);

        $this->assertTrue($response->ok());
        $this->assertSame($codigo, $response->json('codigo'));
    }

    public function test_retorna_erro_quando_ncm_nao_encontrado(): void
    {
        $codigo = '00000000';
        $url = config('focusnfe.URL.production') . NCM::URL . "/{$codigo}";

        Http::fake([
            $url => Http::response(['codigo' => 'nao_encontrado'], 404),
        ]);

        $response = $this->service->get($codigo);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
        $this->assertSame('nao_encontrado', $response->json('codigo'));
    }

    public function test_envia_header_authorization_basic(): void
    {
        $codigo = '90049090';
        $url = config('focusnfe.URL.production') . NCM::URL . "/{$codigo}";

        Http::fake([
            $url => Http::response(NCMStub::consultaNCM()[0], 200),
        ]);

        $this->service->get($codigo);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Basic ' . base64_encode($this->token));
        });
    }
}
