<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\CNAE;
use Sysborg\FocusNfe\tests\mocks\Stub\CnaeStub;

class CNAEServiceTest extends TestCase
{
    private CNAE $service;
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => [
                    'production' => 'https://api.focusnfe.com.br',
                ],
            ],
        ]));
        $container->instance('http', new HttpFactory());
        $container->instance('log', new class () {
            public function error(string $message, array $context = []): void
            {
            }
        });
        Container::setInstance($container);
        Facade::setFacadeApplication($container);
        if (!class_exists('Log')) {
            class_alias(LogFacade::class, 'Log');
        }

        $this->service = new CNAE($this->token, $this->ambiente);
    }

    public function test_lista_cnaes_com_sucesso(): void
    {
        $url = config('focusnfe.URL.production') . CNAE::URL . '?offset=1';

        Http::fake([
            $url => Http::response(json_decode(CnaeStub::lista(), true), 200),
        ]);

        $response = $this->service->list();

        $this->assertTrue($response->ok());
        $this->assertIsArray($response->json());
        $this->assertArrayHasKey('codigo', $response->json()[0]);
    }

    public function test_lista_cnaes_com_offset_customizado(): void
    {
        $offset = 20;
        $url = config('focusnfe.URL.production') . CNAE::URL . "?offset={$offset}";

        Http::fake([
            $url => Http::response(json_decode(CnaeStub::lista(), true), 200),
        ]);

        $this->service->list($offset);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url;
        });
    }

    public function test_busca_cnae_por_codigo_com_sucesso(): void
    {
        $codigo = '90050910';
        $url = config('focusnfe.URL.production') . CNAE::URL . "/{$codigo}";

        Http::fake([
            $url => Http::response(json_decode(CnaeStub::detalhe(), true), 200),
        ]);

        $response = $this->service->get($codigo);

        $this->assertTrue($response->ok());
        $this->assertSame($codigo, $response->json('codigo'));
        $this->assertArrayHasKey('descricao_completa', $response->json());
    }

    public function test_retorna_erro_quando_cnae_nao_encontrado(): void
    {
        $codigo = '00000000';
        $url = config('focusnfe.URL.production') . CNAE::URL . "/{$codigo}";

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
        $url = config('focusnfe.URL.production') . CNAE::URL . '?offset=1';

        Http::fake([
            $url => Http::response(json_decode(CnaeStub::lista(), true), 200),
        ]);

        $this->service->list();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Basic ' . base64_encode($this->token));
        });
    }

    public function test_monta_url_consulta_corretamente(): void
    {
        $codigo = '90050910';
        $expectedUrl = config('focusnfe.URL.production') . CNAE::URL . "/{$codigo}";

        Http::fake([
            $expectedUrl => Http::response(json_decode(CnaeStub::detalhe(), true), 200),
        ]);

        $this->service->get($codigo);

        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });
    }
}
