<?php

namespace Sysborg\FocusNfe\tests\Feature;

use Sysborg\FocusNfe\tests\mocks\Stub\CEPStub;
use Sysborg\FocusNfe\app\Services\CEP;
use Sysborg\FocusNfe\tests\mocks\CEPMock;

class CEPControllerTest extends Common
{

    use CEPMock;

    /**
     * Teste de consulta de CEP com sucesso.
     * 
     * @return void
     */
    public function test_consulta_cep_sucesso(): void
    {
        $cep = "69900932";
        $expectedKeys = array_keys(json_decode(CEPStub::sucesso(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CEP::URL . '/' . $cep,
            'sucesso',
            200
        );

        $response = $this->get($this->prefix . '/cep/' . $cep);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de CEP não encontrado.
     * 
     * @return void
     */
    public function test_consulta_cep_nao_encontrado(): void
    {
        $cep = "00000000";
        $expectedKeys = array_keys(json_decode(CEPStub::cepNaoEncontrado(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CEP::URL . '/' . $cep,
            'cepNaoEncontrado',
            404
        );

        $response = $this->get($this->prefix . '/cep/' . $cep);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'CEP não encontrado na base de dados');
    }

    /**
     * Teste de consulta de CEP com requisição inválida.
     * 
     * @return void
     */
    public function test_consulta_cep_requisicao_invalida(): void
    {
        $cep = "ABCDE123";
        $expectedKeys = array_keys(json_decode(CEPStub::requisicaoInvalida(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CEP::URL . '/' . $cep,
            'requisicaoInvalida',
            400
        );

        $response = $this->get($this->prefix . '/cep/' . $cep);
        $json = $response->json();

        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'requisicao_invalida');
        $response->assertEquals($json['mensagem'], 'CEP informado está em formato inválido');
    }
}
