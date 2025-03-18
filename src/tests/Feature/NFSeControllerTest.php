<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeStub;
use Sysborg\FocusNFe\tests\mocks\NFSeMock;
use Sysborg\FocusNFe\app\Services\NFSe;

class NFSeControllerTest extends Common
{
    use NFSeMock;

    /**
     * Teste de emissão de NFSe com sucesso
     */
    public function test_emissao_nfse_sucesso(): void
    {
        $inputData = NFSeStub::request();
        $expectedKeys = array_keys(json_decode(NFSeStub::autorizada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '?ref=nfs-2',
            'autorizada',
            201
        );

        $response = $this->post($this->prefix . NFSe::URL . '?ref=nfs-2', $inputData);
        $response->assertStatus(201);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para NFSe processando autorização
     */
    public function test_emissao_nfse_processando_autorizacao(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::processandoAutorizacao(), true));

        $inputData = NFSeStub::request();

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '?ref=nfs-2',
            'processandoAutorizacao',
            202
        );

        $response = $this->post($this->prefix . NFSe::URL . '?ref=nfs-2', $inputData);
        $response->assertStatus(202);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para requisição inválida
     */
    public function test_requisicao_invalida_nfse(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::requisicaoInvalida(), true));

        $inputData = [];

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL,
            'requisicaoInvalida',
            400
        );

        $response = $this->post($this->prefix . NFSe::URL, $inputData);
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta NFSe autorizada
     */
    public function test_consulta_nfse_autorizada(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::autorizada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
            'autorizada',
            200
        );

        $response = $this->get($this->prefix . NFSe::URL . '/nfs-2');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de NFSe
     */
    public function test_cancelamento_nfse(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::cancelada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
            'cancelada',
            200
        );

        $response = $this->delete($this->prefix . NFSe::URL . '/nfs-2');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para erro no cancelamento da NFSe
     */
    public function test_erro_cancelamento_nfse(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::erroCancelamento(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
            'erroCancelamento',
            400
        );

        $response = $this->delete($this->prefix . NFSe::URL . '/nfs-2');
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para NFSe já cancelada
     */
    public function test_nfse_ja_cancelada(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::canceladaJaCancelada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
            'canceladaJaCancelada',
            400
        );

        $response = $this->delete($this->prefix . NFSe::URL . '/nfs-2');
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para erro na autorização da NFSe
     */
    public function test_erro_autorizacao_nfse(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeStub::erroAutorizacao(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
            'erroAutorizacao',
            200
        );

        $response = $this->get($this->prefix . NFSe::URL . '/nfs-2');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
 * Teste de consulta NFSe processando autorização
 */
public function test_consulta_nfse_processando_autorizacao(): void
{
    $expectedKeys = array_keys(json_decode(NFSeStub::erroProcessandoAutorizacao(), true));

    $this->mockHttp(
        config('focusnfe.URL.production') . NFSe::URL . '/nfs-2',
        'erroProcessandoAutorizacao',
        200
    );

    $response = $this->get($this->prefix . NFSe::URL . '/nfs-2');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}
}
