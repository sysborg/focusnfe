<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\CFOPStub;
use Sysborg\FocusNFe\app\Services\CFOP;
use Sysborg\FocusNFe\tests\mocks\CFOPMock;

class CFOPControllerTest extends Common
{
    use CFOPMock;

    /**
     * Teste de consulta de CFOP com sucesso.
     * 
     * @return void
     */
    public function test_consulta_cfop_sucesso(): void
    {
        $codigo = "2151";
        $expectedKeys = array_keys(json_decode(CFOPStub::sucesso(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CFOP::URL . '/' . $codigo,
            'sucesso',
            200
        );

        $response = $this->get($this->prefix . '/cfop/' . $codigo);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de CFOP não encontrado.
     * 
     * @return void
     */
    public function test_consulta_cfop_nao_encontrado(): void
    {
        $codigo = "9999";
        $expectedKeys = array_keys(json_decode(CFOPStub::naoEncontrado(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CFOP::URL . '/' . $codigo,
            'naoEncontrado',
            404
        );

        $response = $this->get($this->prefix . '/cfop/' . $codigo);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'Código CFOP não encontrado');
    }

    /**
     * Teste de consulta de CFOP com requisição inválida.
     * 
     * @return void
     */
    public function test_consulta_cfop_requisicao_invalida(): void
    {
        $codigo = "A123";
        $expectedKeys = array_keys(json_decode(CFOPStub::requisicaoInvalida(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CFOP::URL . '/' . $codigo,
            'requisicaoInvalida',
            400
        );

        $response = $this->get($this->prefix . '/cfop/' . $codigo);
        $json = $response->json();

        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'requisicao_invalida');
        $response->assertEquals($json['mensagem'], "Parâmetro 'codigo' inválido ou ausente");
    }

    /**
     * Teste de consulta da lista de CFOPs.
     * 
     * @return void
     */
    public function test_consulta_lista_cfop(): void
    {
        $expectedKeys = array_keys(json_decode(CFOPStub::listaCFOPs(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CFOP::URL,
            'listaCFOPs',
            200
        );

        $response = $this->get($this->prefix . '/cfop');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }
}
