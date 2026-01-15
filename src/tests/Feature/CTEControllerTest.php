<?php

namespace Sysborg\FocusNfe\tests\Feature;

use Sysborg\FocusNfe\tests\mocks\Stub\CTeStub;
use Sysborg\FocusNfe\tests\mocks\CTeMock;
use Sysborg\FocusNfe\app\Services\CTe;

class CTeControllerTest extends Common
{
    use CTeMock;

    /**
     * Teste de criação de CT-e com sucesso
     */
    public function test_criacao_cte_sucesso(): void
    {
        $inputData = CTeStub::request();
        $expectedKeys = array_keys(CTeStub::consultaCTeSucesso());

        $this->mockHttp(
            config('focusnfe.URL.production') . '/v2/cte?ref=' . $inputData['cnpj_emitente'] . '_CTE_000001',
            'consultaCTeSucesso',
            200
        );

        $response = $this->post($this->prefix . '/v2/cte?ref=' . $inputData['cnpj_emitente'] . '_CTE_000001', $inputData);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para erro de requisição inválida
     */
    public function test_erro_requisicao_invalida_cte(): void
    {
        $expectedKeys = array_keys(CTeStub::erroRequisicaoInvalida());

        $payloadInvalido = [];

        $this->mockHttp(
            config('focusnfe.URL.production') . '/v2/cte',
            'erroRequisicaoInvalida',
            400
        );

        $response = $this->post($this->prefix . '/v2/cte', $payloadInvalido);
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de CT-e autorizado com sucesso
     */
    public function test_consulta_cte_sucesso(): void
    {
        $expectedKeys = array_keys(CTeStub::consultaCTeSucesso());

        $this->mockHttp(
            config('focusnfe.URL.production') . '/v2/cte/ref123',
            'consultaCTeSucesso',
            200
        );

        $response = $this->get($this->prefix . '/v2/cte/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de CT-e com sucesso
     */
    public function test_cancelamento_cte_sucesso(): void
    {
        $expectedKeys = array_keys(CTeStub::cancelamentoCTeSucesso());

        $this->mockHttp(
            config('focusnfe.URL.production') . '/v2/cte/ref123',
            'cancelamentoCTeSucesso',
            200
        );

        $response = $this->delete($this->prefix . '/v2/cte/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de carta de correção eletrônica (CC-e) do CT-e com sucesso
     */
    public function test_carta_correcao_cte_sucesso(): void
    {
        $expectedKeys = array_keys(CTeStub::cartaCorrecaoCTeSucesso());

        $payload = [
            'correcao' => 'Texto da correção da CC-e',
            'grupo_alterado' => 'dados do tomador'
        ];

        $this->mockHttp(
            config('focusnfe.URL.production') . '/v2/cte/ref123/carta_correcao',
            'cartaCorrecaoCTeSucesso',
            200
        );

        $response = $this->post($this->prefix . '/v2/cte/ref123/carta_correcao', $payload);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }
}