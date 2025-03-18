<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NFeRecebidasStub;
use Sysborg\FocusNFe\app\Services\NFeRecebidas;
use Sysborg\FocusNFe\tests\mocks\NFeRecebidasMock;

class NFeRecebidasControllerTest extends Common
{
    use NFeRecebidasMock;

    /**
     * Teste de consulta de manifestação de NFe recebida com sucesso.
     * 
     * @return void
     */
    public function test_consulta_manifestacao_sucesso(): void
    {
        $expectedKeys = array_keys(NFeRecebidasStub::consultarManifestacao());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFeRecebidas::URL . '/manifestacao',
            'consultarManifestacao',
            200
        );

        $response = $this->get($this->prefix . '/nfe-recebidas/manifestacao');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de erro ao registrar uma manifestação inválida.
     * 
     * @return void
     */
    public function test_registrar_manifestacao_erro(): void
    {
        $inputData = NFeRecebidasStub::registrarManifestacaoErro();
        $expectedKeys = array_keys(NFeRecebidasStub::erroManifestacao());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFeRecebidas::URL . '/manifestacao',
            'erroManifestacao',
            400
        );

        $response = $this->post($this->prefix . '/nfe-recebidas/manifestacao', $inputData);
        $json = $response->json();

        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['status'], 'erro');
        $response->assertEquals($json['status_sefaz'], '999');
        $response->assertEquals($json['mensagem_sefaz'], 'Justificativa inválida ou não informada');
    }
}
