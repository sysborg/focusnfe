<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CnpjResponseDTO;
use Sysborg\FocusNfe\app\Services\Cnpjs;
use Sysborg\FocusNfe\tests\mocks\Stub\CnpjStub;

class CnpjsServiceTest extends TestCase
{
    private Cnpjs $service;
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

        $this->service = new Cnpjs($this->token, $this->ambiente);
    }

    public function test_consulta_cnpj_com_sucesso(): void
    {
        $cnpj = '07504505000132';
        $url = config('focusnfe.URL.production') . Cnpjs::URL . "/{$cnpj}";

        Http::fake([
            $url => Http::response(json_decode(CnpjStub::sucesso(), true), 200),
        ]);

        $response = $this->service->get($cnpj);

        $this->assertTrue($response->ok());
        $this->assertSame($cnpj, $response->json('cnpj'));
        $this->assertSame('CURITIBA', $response->json('endereco.nome_municipio'));
    }

    public function test_get_dto_converte_resposta_em_objeto_tipado(): void
    {
        $cnpj = '07504505000132';
        $url = config('focusnfe.URL.production') . Cnpjs::URL . "/{$cnpj}";

        Http::fake([
            $url => Http::response(json_decode(CnpjStub::sucesso(), true), 200),
        ]);

        $dto = $this->service->getDto($cnpj);

        $this->assertInstanceOf(CnpjResponseDTO::class, $dto);
        $this->assertSame('ACRAS TECNOLOGIA DA INFORMACAO LTDA', $dto->razao_social);
        $this->assertFalse($dto->optante_simples_nacional);
        $this->assertNotNull($dto->endereco);
        $this->assertSame('4106902', $dto->endereco->codigo_ibge);
        $this->assertSame('PR', $dto->endereco->uf);
    }

    public function test_get_dto_retorna_nulo_quando_api_falha(): void
    {
        $cnpj = '00000000000000';
        $url = config('focusnfe.URL.production') . Cnpjs::URL . "/{$cnpj}";

        Http::fake([
            $url => Http::response(json_decode(CnpjStub::erroCnpjNaoEncontrado(), true), 404),
        ]);

        $dto = $this->service->getDto($cnpj);

        $this->assertNull($dto);
    }
}
