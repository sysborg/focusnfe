<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\MDFeStub;
use Illuminate\Support\Facades\Http;

trait MDFEMock
{

    /**
     * Stub para requisição de cancelamento de MDF-e
     *
     * @param string $url
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(MDFeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(MDFeStub::$stub(), $status)
        ]);
    }

     /**
     * Simula uma requisição bem-sucedida para consultar um MDF-e autorizado.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaMDFe(string $url): void
    {
        $this->mockHttp($url, 'consultaMDFe', 200);
    }

    /**
     * Simula um cancelamento bem-sucedido de MDF-e.
     *
     * @param string $url
     * @return void
     */
    public function mockCancelamentoMDFe(string $url): void
    {
        $this->mockHttp($url, 'cancelamentoMDFe', 200);
    }

    /**
     * Simula a inclusão de um condutor no MDF-e.
     *
     * @param string $url
     * @return void
     */
    public function mockInclusaoCondutor(string $url): void
    {
        $this->mockHttp($url, 'inclusaoCondutor', 200);
    }

    /**
     * Simula a inclusão de um DFe no MDF-e.
     *
     * @param string $url
     * @return void
     */
    public function mockInclusaoDFe(string $url): void
    {
        $this->mockHttp($url, 'inclusaoDFe', 200);
    }

    /**
     * Simula o encerramento de um MDF-e.
     *
     * @param string $url
     * @return void
     */
    public function mockEncerramentoMDFe(string $url): void
    {
        $this->mockHttp($url, 'encerramentoMDFe', 200);
    }
}
