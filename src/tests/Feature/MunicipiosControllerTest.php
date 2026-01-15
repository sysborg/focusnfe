<?php

namespace Sysborg\FocusNfe\tests\Feature;

use Sysborg\FocusNfe\tests\mocks\Stub\MunicipioStub;
use Sysborg\FocusNfe\app\Services\Municipios;
use Sysborg\FocusNfe\tests\mocks\MunicipioMock;

class MunicipioControllerTest extends Common
{
    use MunicipioMock;

    /**
     * Teste de consulta de municípios com sucesso.
     * 
     * @return void
     */
    public function test_consulta_municipios_sucesso(): void
    {
        $expectedKeys = array_keys(MunicipioStub::consultaMunicipios()[0]);

        $this->mockHttp(
            config('focusnfe.URL.production') . Municipios::URL,
            'consultaMunicipios',
            200
        );

        $response = $this->get($this->prefix . '/municipios');
        $response->assertStatus(200);
        $response->assertJsonStructure([$expectedKeys]);
    }

    /**
     * Teste de consulta de um município pelo código com sucesso.
     * 
     * @return void
     */
    public function test_consulta_municipio_unico_sucesso(): void
    {
        $codigoMunicipio = "4106902";
        $expectedKeys = array_keys(MunicipioStub::consultaMunicipioUnico());

        $this->mockHttp(
            config('focusnfe.URL.production') . Municipios::URL . '/' . $codigoMunicipio,
            'consultaMunicipioUnico',
            200
        );

        $response = $this->get($this->prefix . '/municipios/' . $codigoMunicipio);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta da lista de serviços de um município com sucesso.
     * 
     * @return void
     */
    public function test_consulta_lista_servico_sucesso(): void
    {
        $codigoMunicipio = "4106902";
        $expectedKeys = array_keys(MunicipioStub::consultaListaServico()[0]);

        $this->mockHttp(
            config('focusnfe.URL.production') . Municipios::URL . '/' . $codigoMunicipio . '/servicos',
            'consultaListaServico',
            200
        );

        $response = $this->get($this->prefix . '/municipios/' . $codigoMunicipio . '/servicos');
        $response->assertStatus(200);
        $response->assertJsonStructure([$expectedKeys]);
    }

    /**
     * Teste de consulta de município não encontrado.
     * 
     * @return void
     */
    public function test_consulta_municipio_nao_encontrado(): void
    {
        $codigoMunicipio = "0000000";
        $this->mockHttp(
            config('focusnfe.URL.production') . Municipios::URL . '/' . $codigoMunicipio,
            'erroMunicipioNaoEncontrado',
            404
        );

        $response = $this->get($this->prefix . '/municipios/' . $codigoMunicipio);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'Município não encontrado na base de dados.');
    }
}
