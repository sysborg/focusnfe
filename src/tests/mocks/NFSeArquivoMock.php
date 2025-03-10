<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeArquivoStub;
use Illuminate\Support\Facades\Http;

trait NFSeArquivoMock
{
    /**
     * Stub para requisição HTTP de envio e consulta de NFSe por arquivo
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeArquivoStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFSeArquivoStub::$stub(), $status)
        ]);
    }

     /**
     * Simula a resposta da API para o envio de NFSe via arquivo (sucesso).
     *
     * @param string $url
     * @return void
     */
    public function mockEnviaNFSeArquivoSucesso(string $url): void
    {
        $this->mockHttp($url, 'EnviaNFSeArquivoSucesso', 201);
    }

    /**
     * Simula a resposta da API para erro ao consultar NFSe enviada por arquivo (lote não encontrado).
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFSeArquivoErro(string $url): void
    {
        $this->mockHttp($url, 'consultaNFSeArquivoErro', 400);
    }
}
