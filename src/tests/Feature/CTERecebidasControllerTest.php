<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\CTERecebidasMock;
use Sysborg\FocusNFe\tests\mocks\Stub\CTERecebidasStub;
use Sysborg\FocusNFe\app\Services\CTERecebidas;

class CTERecebidasControllerTest extends Common {
    use CTERecebidasMock;

    /**
     * Teste para consulta das CTEs recebidas por CNPJ com sucesso
     * 
     * @return void
     */
    public function test_consulta_ctes_recebidas_sucesso(): void
    {
        $expectedKeys = array_keys(json_decode(CTERecebidasStub::consultaCTesRecebidas(), true)[0]);

        $cnpj = '12345678000100';
        $this->mockHttp(
            config('focusnfe.URL.production') . CTERecebidas::URL . "?cnpj=$cnpj",
            'consultaCTesRecebidas',
            200
        );

        $response = $this->get($this->prefix . CTERecebidas::URL . "?cnpj=$cnpj");
        $response->assertStatus(200);
        $response->assertJsonStructure([$expectedKeys]);
    }

    /**
     * Teste para consulta individual de CTE recebida bem-sucedida.
     * 
     * @return void
     */
    public function test_consulta_individual_cte_recebida(): void
    {
        $expectedKeys = array_keys(json_decode(CTERecebidasStub::consultaIndividual(), true));

        $chave = '35191008165642000152570020004201831004201839';
        $this->mockHttp(
            config('focusnfe.URL.production') . CTERecebidas::URL . "/$chave",
            'consultaIndividual',
            200
        );

        $response = $this->get($this->prefix . CTERecebidas::URL . "/$chave");
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para informar desacordo em CTE recebida.
     * 
     * @return void
     */
    public function test_informar_desacordo_cte_recebida(): void
    {
        $chave = '35191008165642000152570020004201831004201839';
        $inputData = json_decode(CTERecebidasStub::informarDesacordo(), true);
        $expectedKeys = array_keys(json_decode(CTERecebidasStub::consultaDesacordo(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . CTERecebidas::URL . "/$chave/desacordo",
            'consultaDesacordo',
            201
        );

        $response = $this->post($this->prefix . CTERecebidas::URL . "/$chave/desacordo", $inputData);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para consulta de desacordo já registrado em CTE recebida.
     * 
     * @return void
     */
    public function test_consulta_desacordo_registrado(): void
    {
        $expectedKeys = array_keys(json_decode(CTERecebidasStub::consultaDesacordo(), true));

        $chave = '35191008165642000152570020004201831004201839';

        $this->mockHttp(
            config('focusnfe.URL.production') . CTERecebidas::URL . "/$chave/desacordo",
            'consultaDesacordo',
            200
        );

        $response = $this->get($this->prefix . CTERecebidas::URL . "/$chave/desacordo");
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }
}
