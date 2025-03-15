<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\CnpjStub;
use Sysborg\FocusNFe\app\Services\Cnpjs;
use Sysborg\FocusNFe\tests\mocks\CnpjMock;

class CnpjControllerTest extends Common
{
    use CnpjMock;

    /**
     * Teste de consulta de CNPJ com sucesso.
     * 
     * @return void
     */
    public function test_consulta_cnpj_sucesso(): void
    {
        $cnpj = "07504505000132";
        $expectedKeys = array_keys(json_decode(CnpjStub::sucesso(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . Cnpjs::URL . '/' . $cnpj,
            'sucesso',
            200
        );

        $response = $this->get($this->prefix . '/cnpj/' . $cnpj);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de erro ao consultar CNPJ não encontrado.
     * 
     * @return void
     */
    public function test_consulta_cnpj_nao_encontrado(): void
    {
        $cnpj = "00000000000000";
        $expectedKeys = array_keys(json_decode(CnpjStub::erroCnpjNaoEncontrado(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . Cnpjs::URL . '/' . $cnpj,
            'erroCnpjNaoEncontrado',
            404
        );

        $response = $this->get($this->prefix . '/cnpj/' . $cnpj);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'CNPJ não encontrado na base de dados.');
    }
}
