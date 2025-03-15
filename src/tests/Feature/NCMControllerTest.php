<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NCMStub;
use Sysborg\FocusNFe\app\Services\NCM;
use Sysborg\FocusNFe\tests\mocks\NCMMock;

class NCMControllerTest extends Common
{
    use NCMMock;

    /**
     * Teste de consulta da lista de NCMs.
     * 
     * @return void
     */
    public function test_consulta_lista_ncm(): void
    {
        $expectedKeys = array_keys(NCMStub::consultaNCM()[0]);

        $this->mockHttp(
            config('focusnfe.URL.production') . NCM::URL,
            'consultaNCM',
            200
        );

        $response = $this->get($this->prefix . '/ncm');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => $expectedKeys
        ]);
    }
}
