<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeNacionalStub;
use Sysborg\FocusNFe\tests\mocks\NFSeNacionalMock;
use Sysborg\FocusNFe\app\Services\NFSeNacional;

class NFSeNacionalControllerTest extends Common
{
    use NFSeNacionalMock;

    /**
     * Teste de emissão de NFSe Nacional com sucesso
     */
    public function test_emissao_nfse_nacional_sucesso(): void
    {
        $inputData = NFSeNacionalStub::request();
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::autorizada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL . '?ref=12345',
            'autorizada',
            201
        );

        $response = $this->post($this->prefix . NFSeNacional::URL . '?ref=12345', $inputData);
        $response->assertStatus(201);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para NFSe Nacional processando autorização
     */
    public function test_emissao_nfse_nacional_processando_autorizacao(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::processandoAutorizacaoEnvio(), true));

        $inputData = NFSeNacionalStub::request();

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL . '?ref=12345',
            'processandoAutorizacaoEnvio',
            202
        );

        $response = $this->post($this->prefix . NFSeNacional::URL . '?ref=12345', $inputData);
        $response->assertStatus(202);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para requisição inválida
     */
    public function test_requisicao_invalida_nfse_nacional(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::requisicaoInvalida(), true));

        $inputData = [];

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL,
            'requisicaoInvalida',
            400
        );

        $response = $this->post($this->prefix . NFSeNacional::URL, $inputData);
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta NFSe Nacional autorizada
     */
    public function test_consulta_nfse_nacional_autorizada(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::autorizada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
            'autorizada',
            200
        );

        $response = $this->get($this->prefix . NFSeNacional::URL . '/12345');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de cancelamento de NFSe Nacional
     */
    public function test_cancelamento_nfse_nacional(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::cancelada(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
            'cancelada',
            200
        );

        $response = $this->delete($this->prefix . NFSeNacional::URL . '/12345');
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste para erro no cancelamento da NFSe Nacional
     */
    public function test_erro_cancelamento_nfse_nacional(): void
    {
        $expectedKeys = array_keys(json_decode(NFSeNacionalStub::erroCancelamento(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
            'erroCancelamento',
            400
        );

        $response = $this->delete($this->prefix . NFSeNacional::URL . '/12345');
        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
 * Teste para consulta de NFSe Nacional processando autorização
 */
public function test_consulta_nfse_nacional_processando_autorizacao(): void
{
    $expectedKeys = array_keys(json_decode(NFSeNacionalStub::processandoAutorizacaoConsulta(), true));

    $this->mockHttp(
        config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
        'processandoAutorizacaoConsulta',
        200
    );

    $response = $this->get($this->prefix . NFSeNacional::URL . '/12345');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para erro na autorização da NFSe Nacional
 */
public function test_erro_autorizacao_nfse_nacional(): void
{
    $expectedKeys = array_keys(json_decode(NFSeNacionalStub::erroAutorizacao(), true));

    $this->mockHttp(
        config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
        'erroAutorizacao',
        200
    );

    $response = $this->get($this->prefix . NFSeNacional::URL . '/12345');
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
}

/**
 * Teste para tentativa de cancelamento de uma NFSe Nacional já cancelada
 */
public function test_cancelamento_nfse_nacional_ja_cancelada(): void
{
    $expectedKeys = array_keys(json_decode(NFSeNacionalStub::nfseJaCancelada(), true));

    $this->mockHttp(
        config('focusnfe.URL.production') . NFSeNacional::URL . '/12345',
        'nfseJaCancelada',
        400
    );

    $response = $this->delete($this->prefix . NFSeNacional::URL . '/12345');
    $response->assertStatus(400);
    $response->assertJsonStructure($expectedKeys);
}

}
