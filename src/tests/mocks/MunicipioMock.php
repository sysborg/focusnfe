<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Sysborg\FocusNfe\tests\mocks\Stub\MunicipiosStub;
use Illuminate\Support\Facades\Http;

trait MunicipioMock
{
    /**
     * Stub para requisição de municípios
     *
     * @param string $url
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, int $status, int $times = 1): void
    {
        Http::fake([
            $url => Http::response(MunicipiosStub::consultaMunicipios(), $status)
        ]);
    }

     /**
     * Simula a consulta de todos os municípios.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaMunicipios(string $url): void
    {
        $this->mockHttp($url, 'consultaMunicipios', 200);
    }

    /**
     * Simula a consulta de um único município pelo código.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaMunicipioUnico(string $url): void
    {
        $this->mockHttp($url, 'consultaMunicipioUnico', 200);
    }

    /**
     * Simula a consulta da lista de serviços de um município.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaListaServico(string $url): void
    {
        $this->mockHttp($url, 'consultaListaServico', 200);
    }

    /**
     * Simula um erro de município não encontrado.
     *
     * @param string $url
     * @return void
     */
    public function mockMunicipioNaoEncontrado(string $url): void
    {
        Http::fake([
            $url => Http::response([
                "codigo" => "nao_encontrado",
                "mensagem" => "Município não encontrado na base de dados."
            ], 404)
        ]);
    }

    /**
     * Simula uma requisição inválida ao consultar um município.
     *
     * @param string $url
     * @return void
     */
    public function mockRequisicaoInvalida(string $url): void
    {
        Http::fake([
            $url => Http::response([
                "codigo" => "requisicao_invalida",
                "mensagem" => "Código do município inválido ou ausente."
            ], 400)
        ]);
    }
}
