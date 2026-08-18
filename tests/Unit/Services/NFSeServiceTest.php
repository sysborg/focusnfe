<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFSeConsultaResponseDTO;
use Sysborg\FocusNfe\app\Services\NFSe;
use Sysborg\FocusNfe\tests\mocks\Stub\NFSeStub;

class NFSeServiceTest extends TestCase
{
    private NFSe $service;

    private string $token = 'test-token-123';

    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container;
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => 'https://api.focusnfe.com.br'],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
            ],
        ]));
        $container->instance('http', new HttpFactory);
        $container->instance('events', new class
        {
            public function dispatch($event): void {}
        });
        $container->instance('log', new class
        {
            public function channel(?string $channel = null): static
            {
                return $this;
            }

            public function error(string $message, array $context = []): void {}

            public function debug(string $message, array $context = []): void {}

            public function info(string $message, array $context = []): void {}

            public function warning(string $message, array $context = []): void {}
        });

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        if (! class_exists('Log')) {
            class_alias(LogFacade::class, 'Log');
        }

        $this->service = new NFSe($this->token, $this->ambiente);
    }

    public function test_get_nfse_preserva_response_original(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production').NFSe::URL."/{$referencia}";

        Http::fake([
            $url => Http::response(json_decode(NFSeStub::autorizada(), true), 200),
        ]);

        $response = $this->service->get($referencia);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->ok());
        $this->assertSame('autorizado', $response->json('status'));
    }

    public function test_get_dto_retorna_nfse_processando_autorizacao(): void
    {
        $dto = $this->fakeConsultaDto('nfs-processando', NFSeStub::processandoAutorizacao(), 200);

        $this->assertInstanceOf(NFSeConsultaResponseDTO::class, $dto);
        $this->assertSame(200, $dto->http_status);
        $this->assertTrue($dto->processandoAutorizacao());
        $this->assertSame('12345678000123', $dto->cnpj_prestador);
        $this->assertSame('1', $dto->tipo_rps);
    }

    public function test_get_dto_retorna_nfse_autorizada(): void
    {
        $dto = $this->fakeConsultaDto('nfs-autorizada', NFSeStub::autorizada(), 200);

        $this->assertTrue($dto->autorizada());
        $this->assertSame('233', $dto->numero);
        $this->assertSame('DU1M-M2Y', $dto->codigo_verificacao);
        $this->assertNotNull($dto->caminho_xml_nota_fiscal);
        $this->assertNotNull($dto->url_danfse);
    }

    public function test_get_dto_retorna_nfse_cancelada(): void
    {
        $dto = $this->fakeConsultaDto('nfs-cancelada', NFSeStub::cancelada(), 200);

        $this->assertTrue($dto->cancelada());
        $this->assertSame('233', $dto->numero);
        $this->assertNotNull($dto->caminho_xml_cancelamento);
        $this->assertNotNull($dto->url_danfse);
    }

    public function test_get_dto_retorna_erro_autorizacao_com_erros(): void
    {
        $dto = $this->fakeConsultaDto('nfs-erro', NFSeStub::erroAutorizacao(), 200);

        $this->assertTrue($dto->erroAutorizacao());
        $this->assertCount(1, $dto->erros);
        $this->assertSame('E145', $dto->erros[0]['codigo']);
        $this->assertSame('Regime Especial de Tributação ausente/inválido.', $dto->erros[0]['mensagem']);
    }

    public function test_get_dto_retorna_nfse_nao_encontrada(): void
    {
        $dto = $this->fakeConsultaDto('nfs-inexistente', NFSeStub::naoEncontrada(), 404);

        $this->assertSame(404, $dto->http_status);
        $this->assertTrue($dto->naoEncontrada());
        $this->assertSame('nao_encontrado', $dto->codigo);
        $this->assertSame('Nota fiscal não encontrada', $dto->mensagem);
    }

    private function fakeConsultaDto(string $referencia, string $body, int $status): NFSeConsultaResponseDTO
    {
        $url = config('focusnfe.URL.production').NFSe::URL."/{$referencia}";

        Http::fake([
            $url => Http::response(json_decode($body, true), $status),
        ]);

        return $this->service->getDto($referencia);
    }
}
