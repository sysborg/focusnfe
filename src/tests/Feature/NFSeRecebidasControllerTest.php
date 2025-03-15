<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeRecebidasStub;
use Sysborg\FocusNFe\app\Services\NFSeRecebidas;
use Sysborg\FocusNFe\tests\mocks\NFSeRecebidasMock;

class NFSeRecebidasControllerTest extends Common
{
    use NFSeRecebidasMock;

    /**
     * Teste de consulta de todas as NFSe recebidas para um CNPJ.
     * 
     * @return void
     */
    public function test_consulta_todas_nfse_recebidas(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeRecebidasStub::todasNfseRecebidas(), true)[0]);

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeRecebidas::URL,
            'todasNfseRecebidas',
            200
        );

        $response = $this->get($this->prefix . '/nfse-recebidas');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => $expectedKeys]);
    }

    /**
     * Teste de consulta de uma NFSe específica pela chave.
     * 
     * @return void
     */
    public function test_consulta_nfse_por_chave(): void
    {
        $chave = "NFSe859042900001504305108-5555-123456-DMMY000";
        $expectedKeys = array_keys(json_decode(NFSeRecebidasStub::consultaNfsePorChave(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeRecebidas::URL . '/' . $chave,
            'consultaNfsePorChave',
            200
        );

        $response = $this->get($this->prefix . '/nfse-recebidas/' . $chave);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de erro ao consultar uma NFSe inexistente.
     * 
     * @return void
     */
    public function test_consulta_nfse_nao_encontrada(): void
    {
        $chave = "NFSeInexistente123";
        $expectedKeys = array_keys(json_decode(NFSeRecebidasStub::erroChaveNaoEncontrada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeRecebidas::URL . '/' . $chave,
            'erroChaveNaoEncontrada',
            404
        );

        $response = $this->get($this->prefix . '/nfse-recebidas/' . $chave);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'NFSe não encontrada para a chave informada.');
    }
}
