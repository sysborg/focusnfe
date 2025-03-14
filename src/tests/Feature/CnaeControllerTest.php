<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\CnaeStub;
use Sysborg\FocusNFe\app\Services\Cnae;
use Sysborg\FocusNFe\tests\mocks\CnaeMock;

class CnaeControllerTest extends Common
{
    use CnaeMock;

    /**
     * Teste de consulta de um CNAE específico com sucesso.
     * 
     * @return void
     */
    public function test_consulta_cnae_sucesso(): void
    {
        $codigo = "90050910";
        $expectedKeys = array_keys(json_decode(CnaeStub::detalhe(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . Cnae::URL . '/' . $codigo,
            'detalhe',
            200
        );

        $response = $this->get($this->prefix . '/cnae/' . $codigo);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }


    /**
     * Teste de consulta da lista de CNAEs.
     * 
     * @return void
     */
    public function test_consulta_lista_cnae(): void
    {
        $expectedKeys = array_keys(json_decode(CnaeStub::lista(), true)[0]);

        $this->mockHttp(
            config('focusnfe.URL.production') . Cnae::URL,
            'lista',
            200
        );

        $response = $this->get($this->prefix . '/cnae');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $expectedKeys
        ]);
    }
}
