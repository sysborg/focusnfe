<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\CEP;
use Sysborg\FocusNfe\tests\mocks\Stub\CEPStub;

class CEPServiceTest extends TestCase
{
    private CEP $service;
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
        $container->instance('log', new class {
            public function error(string $message, array $context = []): void
            {
            }
        });

        Container::setInstance($container);
        Facade::setFacadeApplication($container);

        if (!class_exists('Log')) {
            class_alias(LogFacade::class, 'Log');
        }

        $this->service = new CEP($this->token, $this->ambiente);
    }

    public function test_consulta_cep_com_sucesso(): void
    {
        $cep = '69900932';
        $url = config('focusnfe.URL.production') . CEP::URL . "/{$cep}";

        Http::fake([
            $url => Http::response(json_decode(CEPStub::sucesso(), true), 200),
        ]);

        $response = $this->service->get($cep);

        $this->assertTrue($response->ok());
        $this->assertSame($cep, $response->json('cep'));
        $this->assertArrayHasKey('descricao', $response->json());
    }

    public function test_retorna_erro_quando_cep_nao_encontrado(): void
    {
        $cep = '00000000';
        $url = config('focusnfe.URL.production') . CEP::URL . "/{$cep}";

        Http::fake([
            $url => Http::response(json_decode(CEPStub::cepNaoEncontrado(), true), 404),
        ]);

        $response = $this->service->get($cep);

        $this->assertTrue($response->failed());
        $this->assertSame(404, $response->status());
        $this->assertSame('nao_encontrado', $response->json('codigo'));
    }

    public function test_envia_header_authorization_basic(): void
    {
        $cep = '69900932';
        $url = config('focusnfe.URL.production') . CEP::URL . "/{$cep}";

        Http::fake([
            $url => Http::response(json_decode(CEPStub::sucesso(), true), 200),
        ]);

        $this->service->get($cep);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Basic ' . base64_encode($this->token));
        });
    }

    public function test_monta_url_consulta_corretamente(): void
    {
        $cep = '69900932';
        $expectedUrl = config('focusnfe.URL.production') . CEP::URL . "/{$cep}";

        Http::fake([
            $expectedUrl => Http::response(json_decode(CEPStub::sucesso(), true), 200),
        ]);

        $this->service->get($cep);

        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });
    }
}
