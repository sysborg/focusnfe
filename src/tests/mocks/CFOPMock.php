<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\CFOPStub;
use Illuminate\Support\Facades\Http;

trait CFOPMock {
    
    /**
     * Simula uma requisição HTTP para CFOP.
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(CFOPStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(CFOPStub::$stub(), $status)
        ]);
    }

      /**
     * Simula a busca por um CFOP existente.
     *
     * @param string $url
     * @return void
     */
    public function mockCFOPSucesso(string $url): void
    {
        $this->mockHttp($url, 'sucesso', 200);
    }

    /**
     * Simula a busca por um CFOP que não existe.
     *
     * @param string $url
     * @return void
     */
    public function mockCFOPNaoEncontrado(string $url): void
    {
        $this->mockHttp($url, 'naoEncontrado', 404);
    }

    /**
     * Simula um erro na requisição de CFOP.
     *
     * @param string $url
     * @return void
     */
    public function mockCFOPRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }

    /**
     * Simula a listagem de CFOPs paginada.
     *
     * @param string $url
     * @return void
     */
    public function mockListaCFOPs(string $url): void
    {
        $this->mockHttp($url, 'listaCFOPs', 200);
    }
}
