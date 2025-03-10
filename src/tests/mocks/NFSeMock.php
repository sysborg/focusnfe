<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeStub;
use Illuminate\Support\Facades\Http;

trait NFSeMock {
    /**
     * Stub de requisições HTTP para NFSe.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado na classe NFSeStub");
        }

        Http::fake([
            $url => Http::response(NFSeStub::$stub(), $status)
        ]);
    }

    /**
     * Simula o envio de NFSe com sucesso.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeAutorizada(string $url): void
    {
        $this->mockHttp($url, 'autorizada', 200);
    }

    /**
     * Simula a resposta para uma NFSe que ainda está processando autorização.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeProcessandoAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'processandoAutorizacao', 422);
    }

    /**
     * Simula a resposta de erro na autorização da NFSe.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeErroAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'erroAutorizacao', 400);
    }

    /**
     * Simula o cancelamento de NFSe autorizado.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeCancelada(string $url): void
    {
        $this->mockHttp($url, 'cancelada', 200);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe já cancelada.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeJaCancelada(string $url): void
    {
        $this->mockHttp($url, 'canceladaJaCancelada', 400);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe fora do prazo permitido.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeErroCancelamento(string $url): void
    {
        $this->mockHttp($url, 'erroCancelamento', 400);
    }

    /**
     * Simula erro de requisição inválida ao enviar ou cancelar uma NFSe.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }
}
