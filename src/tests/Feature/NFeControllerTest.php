<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NFeStub;
use Sysborg\FocusNFe\tests\mocks\NFeMock;
use Sysborg\FocusNFe\app\Services\NFe;

class NFeControllerTest extends Common
{
    use NFeMock;

    /**
     * Teste de emissão de NFe com sucesso
     */
    public function test_emissao_nfe_sucesso(): void
    {
        $inputData = NFeStub::request();
        $expectedKeys = array_keys(NFeStub::consultaNFeAutorizada());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFe::URL . '?ref=' . $inputData['cnpj_emitente'] . '_NFE_000001',
            'envioNFeSucesso',
            201
        );

        $response = $this->post($this->prefix . NFe::URL . '?ref=' . $inputData['cnpj_emitente'] . '_NFE_000001', $inputData);
        $response->assertStatus(201);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para erro na validação do schema XML
     */
    public function test_erro_validacao_schema(): void
    {
        $expectedKeys = array_keys(NFeStub::erroValidacaoSchema());

        $payloadInvalido = [];

        $this->mockHttp(
            config('focusnfe.URL.production') . NFe::URL,
            'erroValidacaoSchema',
            400
        );

        $response = $this->post($this->prefix . NFe::URL, $payloadInvalido);
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de NFe autorizada com sucesso
     */
    public function test_consulta_nfe_autorizada(): void
    {
        $expectedKeys = array_keys(NFeStub::consultaNFeAutorizada());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFe::URL . '/ref123',
            'consultaNFeAutorizada',
            200
        );

        $response = $this->get($this->prefix . NFe::URL . '/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de NFe com sucesso
     */
    public function test_cancelamento_nfe_sucesso(): void
    {
        $expectedKeys = array_keys(NFeStub::cancelamentoNFeSucesso());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFe::URL . '/ref123',
            'cancelamentoNFeSucesso',
            200
        );

        $response = $this->delete($this->prefix . NFe::URL . '/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

/**
 * Teste de consulta de NFe ainda em processamento
 */
public function test_consulta_nfe_processando(): void
{
    $expectedKeys = array_keys(NFeStub::consultaNFeProcessando());

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/ref123',
        'consultaNFeProcessando',
        200
    );

    $response = $this->get($this->prefix . NFe::URL . '/ref123');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para erro de autorização de NFe
 */
public function test_consulta_nfe_erro_autorizacao(): void
{
    $expectedKeys = array_keys(NFeStub::consultaNFeErroAutorizacao());

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/ref123',
        'consultaNFeErroAutorizacao',
        200
    );

    $response = $this->get($this->prefix . NFe::URL . '/ref123');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste de consulta de NFe autorizada com Carta de Correção (CC-e) anexada
 */
public function test_consulta_nfe_com_cce(): void
{
    $expectedKeys = array_keys(NFeStub::consultaNFeComCCe());

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/ref123',
        'consultaNFeComCCe',
        200
    );

    $response = $this->get($this->prefix . NFe::URL . '/ref123');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste de consulta de NFe cancelada
 */
public function test_consulta_nfe_cancelada(): void
{
    $expectedKeys = array_keys(NFeStub::consultaNFeCancelada());

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/ref123',
        'consultaNFeCancelada',
        200
    );

    $response = $this->get($this->prefix . NFe::URL . '/ref123');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para erro de cancelamento inválido de NFe
 */
public function test_cancelamento_nfe_erro(): void
{
    $expectedKeys = array_keys(NFeStub::erroCancelamentoNFe());

    $payloadInvalido = ['justificativa' => 'curto'];

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/ref123',
        'erroCancelamentoNFe',
        400
    );

    $response = $this->delete($this->prefix . NFe::URL . '/ref123', $payloadInvalido);
    $response->assertStatus(400);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste de inutilização de numeração da NFe autorizada
 */
public function test_inutilizacao_nfe_sucesso(): void
{
    $expectedKeys = array_keys(NFeStub::inutilizacaoNFeSucesso());

    $payload = [
        'serie' => '1',
        'numero_inicial' => '999',
        'numero_final' => '1000',
        'justificativa' => 'Falha na emissão'
    ];

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/inutilizacao',
        'inutilizacaoNFeSucesso',
        200
    );

    $response = $this->post($this->prefix . NFe::URL . '/inutilizacao', $payload);
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para erro ao inutilizar numeração da NFe
 */
public function test_inutilizacao_nfe_erro(): void
{
    $expectedKeys = array_keys(NFeStub::inutilizacaoNFeErro());

    $payload = [
        'serie' => '1',
        'numero_inicial' => '1000',
        'numero_final' => '1000',
        'justificativa' => 'Falha na emissão'
    ];

    $this->mockHttp(
        config('focusnfe.URL.production') . NFe::URL . '/inutilizacao',
        'inutilizacaoNFeErro',
        422
    );

    $response = $this->post($this->prefix . NFe::URL . '/inutilizacao', $payload);
    $response->assertStatus(422);
    $response->assertJsonStructure($expectedKeys);
}


}
