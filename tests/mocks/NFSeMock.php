<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Illuminate\Support\Facades\Http;
use Sysborg\FocusNfe\tests\mocks\Stub\NFSeStub;

trait NFSeMock
{
    /**
     * Stub de requisições HTTP para NFSe.
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (! method_exists(NFSeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado na classe NFSeStub");
        }

        Http::fake([
            $url => Http::response(NFSeStub::$stub(), $status),
        ]);
    }

    /**
     * Simula o envio de NFSe com sucesso.
     */
    public function mockNFSeAutorizada(string $url): void
    {
        $this->mockHttp($url, 'autorizada', 200);
    }

    /**
     * Simula a resposta para uma NFSe que ainda está processando autorização.
     */
    public function mockNFSeProcessandoAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'processandoAutorizacao', 200);
    }

    /**
     * Simula a resposta de erro na autorização da NFSe.
     */
    public function mockNFSeErroAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'erroAutorizacao', 200);
    }

    /**
     * Simula o cancelamento de NFSe autorizado.
     */
    public function mockNFSeCancelada(string $url): void
    {
        $this->mockHttp($url, 'cancelada', 200);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe já cancelada.
     */
    public function mockNFSeJaCancelada(string $url): void
    {
        $this->mockHttp($url, 'canceladaJaCancelada', 400);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe fora do prazo permitido.
     */
    public function mockNFSeErroCancelamento(string $url): void
    {
        $this->mockHttp($url, 'erroCancelamento', 400);
    }

    /**
     * Simula erro de requisição inválida ao enviar ou cancelar uma NFSe.
     */
    public function mockNFSeRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }

    /**
     * Simula a resposta de NFSe não encontrada.
     */
    public function mockNFSeNaoEncontrada(string $url): void
    {
        $this->mockHttp($url, 'naoEncontrada', 404);
    }
}
