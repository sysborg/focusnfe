<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\CnaeStub;
use Illuminate\Support\Facades\Http;

trait CnaeMock {
    /**
     * Stub de consulta de CNAE
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(CnaeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(CnaeStub::$stub(), $status)
        ]);
    }

    
    /**
     * Simula a consulta da listagem de CNAEs.
     *
     * @param string $url
     * @return void
     */
    public function mockListaCnae(string $url): void
    {
        $this->mockHttp($url, 'lista', 200);
    }

    /**
     * Simula a consulta de um CNAE específico.
     *
     * @param string $url
     * @return void
     */
    public function mockDetalheCnae(string $url): void
    {
        $this->mockHttp($url, 'detalhe', 200);
    }

    /**
     * Simula um erro ao tentar consultar um CNAE inexistente.
     *
     * @param string $url
     * @return void
     */
    public function mockCnaeNaoEncontrado(string $url): void
    {
        $this->mockHttp($url, 'naoEncontrado', 404);
    }

    /**
     * Simula um erro de requisição inválida ao tentar buscar um CNAE.
     *
     * @param string $url
     * @return void
     */
    public function mockRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }
}
