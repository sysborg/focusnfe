<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Illuminate\Support\Facades\Http;
use Sysborg\FocusNfe\tests\mocks\Stub\NFERecebidasStub;

trait NFeRecebidasMock {
    /**
     * Simula a resposta da API para uma manifestação de NFe Recebida.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFERecebidasStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFERecebidasStub::$stub(), $status)
        ]);
    }

      /**
     * Simula a resposta da API para registrar uma manifestação de NFe recebida.
     *
     * @param string $url
     * @return void
     */
    public function mockRegistrarManifestacaoErro(string $url): void
    {
        $this->mockHttp($url, 'registrarManifestacaoErro', 400);
    }

    /**
     * Simula a resposta da API para a consulta de uma manifestação já registrada.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultarManifestacao(string $url): void
    {
        $this->mockHttp($url, 'consultarManifestacao', 200);
    }

    /**
     * Simula a resposta da API para erro ao registrar uma manifestação inválida.
     *
     * @param string $url
     * @return void
     */
    public function mockErroManifestacao(string $url): void
    {
        $this->mockHttp($url, 'erroManifestacao', 400);
    }
}
