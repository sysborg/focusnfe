<?php

namespace Sysborg\FocusNfe\tests\Feature;

use Sysborg\FocusNfe\tests\mocks\MDFeMock;
use Sysborg\FocusNfe\tests\mocks\Stub\MDFeStub;
use Sysborg\FocusNfe\app\Services\MDFe;

class MDFeControllerTest extends Common
{
    use MDFeMock;

    /**
     * Teste de criação de MDF-e com sucesso
     */
    public function test_criacao_mdfe_sucesso(): void
    {
        $inputData = MDFeStub::request();
        $expectedKeys = array_keys(MDFeStub::consultaMDFe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . MDFe::URL . '?ref=' . $inputData['numero'],
            'consultaMDFe',
            200
        );

        $response = $this->post($this->prefix . '/mdfe', $inputData);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de MDF-e
     */
    public function test_cancelamento_mdfe(): void
    {
        $expectedKeys = array_keys(MDFeStub::cancelamentoMDFe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . MDFe::URL . '/' . 'ref123',
            'cancelamentoMDFe',
            200
        );

        $response = $this->delete($this->prefix . '/mdfes/ref123');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de inclusão de condutor em MDF-e
     */
    public function test_inclusao_condutor(): void
    {
        $expectedKeys = array_keys(MDFeStub::inclusaoCondutor());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . MDFe::URL . '/ref123/inclusao_condutor',
            'inclusaoCondutor',
            200
        );

        $response = $this->post($this->prefix . '/mdfes/ref123/inclusao_condutor');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de inclusão de DFe em MDF-e
     */
    public function test_inclusao_dfe(): void
    {
        $expectedKeys = array_keys(MDFeStub::inclusaoDFe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . MDFe::URL . '/ref123/inclusao_dfe',
            'inclusaoDFe',
            200
        );

        $response = $this->post($this->prefix . '/mdfe/ref123/inclusao_dfe');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de encerramento de MDF-e
     */
    public function test_encerramento_mdfe(): void
    {
        $expectedKeys = array_keys(MDFeStub::encerramentoMDFe());
        
        $this->mockHttp(
            config('focusnfe.URL.production') . MDFe::URL . '/ref123/encerrar',
            'encerramentoMDFe',
            200
        );

        $response = $this->post($this->prefix . '/mdfes/ref123/encerrar');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }
}