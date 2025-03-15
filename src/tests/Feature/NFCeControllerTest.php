<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\NFCeMock;
use Sysborg\FocusNFe\tests\mocks\Stub\NFCeStub;
use Sysborg\FocusNFe\app\Services\NFCe;

class NFCeControllerTest extends Common
{
    use NFCeMock;

    /**
     * Teste de criação de NFC-e com sucesso
     */
    public function test_criacao_nfce_sucesso(): void
    {
        $inputData = NFCeStub::request();
        $expectedKeys = array_keys(NFCeStub::consultaNFCe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '?ref=' . $inputData['cnpj_emitente'] . '_NFCE_000001',
            'consultaNFCe',
            200
        );

        $response = $this->post($this->prefix . NFCe::URL . '?ref=' . $inputData['cnpj_emitente'] . '_NFCE_000001', $inputData);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de NFC-e
     */
    public function test_cancelamento_nfce(): void
    {
        $expectedKeys = array_keys(NFCeStub::cancelamentoNFCe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '/ref123',
            'cancelamentoNFCe',
            200
        );

        $response = $this->delete($this->prefix . NFCe::URL . '/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de conciliação financeira da NFC-e
     */
    public function test_consulta_econf(): void
    {
        $expectedKeys = array_keys(NFCeStub::consultaEConf());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '/ref123/econf/335250000000445',
            'consultaEConf',
            200
        );

        $response = $this->get($this->prefix . NFCE::URL . '/ref123/econf/335250000000445');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de registro de conciliação financeira da NFC-e
     */
    public function test_registra_econf(): void
    {
        $expectedKeys = array_keys(NFCeStub::registraEConf());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '/ref123/econf',
            'registraEConf',
            200
        );

        $response = $this->post($this->prefix . NFCe::URL .'/ref123/econf');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento da conciliação financeira da NFC-e
     */
    public function test_cancelamento_econf(): void
    {
        $expectedKeys = array_keys(NFCeStub::cancelaEConf());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '/ref123/econf/335250000000445',
            'cancelaEConf',
            200
        );

        $response = $this->delete($this->prefix . NFCE::URL . '/ref123/econf/335250000000445');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

        /**
     * Teste de inutilização de numeração da NFC-e
     */
    public function test_inutilizacao_nfce(): void
    {
        $expectedKeys = array_keys(NFCeStub::inutilizacaoNFCe());

        $payload = [
            'serie' => '1',
            'numero_inicial' => '999',
            'numero_final' => '1000',
            'justificativa' => 'Falha na emissão'
        ];

        $this->mockHttp(
            config('focusnfe.URL.production') . NFCe::URL . '/inutilizacao',
            'inutilizacaoNFCe',
            200
        );

        $response = $this->post($this->prefix . NFCe::URL . '/inutilizacao', $payload);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
 * Teste para erro na inutilização de numeração da NFC-e
 */
public function test_erro_inutilizacao_nfce(): void
{
    $expectedKeys = array_keys(NFCeStub::erroInutilizacaoNFCe());

    $payload = [
        'serie' => '1',
        'numero_inicial' => '1',
        'numero_final' => '9',
        'justificativa' => 'Teste erro inutilização'
    ];

    $this->mockHttp(
        config('focusnfe.URL.production') . NFCe::URL . '/inutilizacao',
        'erroInutilizacaoNFCe',
        400
    );

    $response = $this->post($this->prefix . NFCe::URL . '/inutilizacao', $payload);
    $response->assertStatus(400);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para erro no cancelamento da NFC-e
 */
public function test_erro_cancelamento_nfce(): void
{
    $expectedKeys = array_keys(NFCeStub::erroCancelamentoNFCe());

    $this->mockHttp(
        config('focusnfe.URL.production') . NFCe::URL . '/ref123',
        'erroCancelamentoNFCe',
        400
    );

    $response = $this->delete($this->prefix . NFCe::URL . '/ref123');
    $response->assertStatus(400);
    $response->assertJsonStructure($expectedKeys);
}

    
}
