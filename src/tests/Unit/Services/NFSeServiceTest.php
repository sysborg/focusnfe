<?php

namespace Sysborg\FocusNFe\tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNFe\app\Services\NFSe;
use Sysborg\FocusNFe\tests\Traits\NFSeDataTrait;
use Sysborg\FocusNFe\tests\Traits\MockHttpResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;

class NFSeServiceTest extends TestCase
{
    use NFSeDataTrait, MockHttpResponseTrait;

    private NFSe $service;
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        // Mock de configuração
        config(['focusnfe.URL.production' => 'https://api.focusnfe.com.br']);

        $this->service = new NFSe($this->token, $this->ambiente);

        // Mock de eventos
        Event::fake();
    }

    /**
     * Testa envio de NFSe com sucesso (autorizada)
     */
    public function test_envia_nfse_com_sucesso(): void
    {
        $nfse = $this->getNFSeValido();
        $url = config('focusnfe.URL.production') . NFSe::URL;

        $this->mockNFSeAutorizada($url);

        $response = $this->service->envia($nfse);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('autorizado', $response['status']);
        $this->assertArrayHasKey('numero', $response);
        $this->assertArrayHasKey('codigo_verificacao', $response);
    }

    /**
     * Testa envio de NFSe que fica processando autorização
     */
    public function test_envia_nfse_processando_autorizacao(): void
    {
        $nfse = $this->getNFSeValido();
        $url = config('focusnfe.URL.production') . NFSe::URL;

        $this->mockNFSeProcessandoAutorizacao($url);

        $response = $this->service->envia($nfse);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('processando_autorizacao', $response['status']);
    }

    /**
     * Testa envio de NFSe com erro de autorização
     */
    public function test_envia_nfse_com_erro_autorizacao(): void
    {
        $nfse = $this->getNFSeValido();
        $url = config('focusnfe.URL.production') . NFSe::URL;

        $this->mockNFSeErroAutorizacao($url);

        $response = $this->service->envia($nfse);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('erro_autorizacao', $response['status']);
        $this->assertArrayHasKey('erros', $response);
    }

    /**
     * Testa envio de NFSe com requisição inválida
     */
    public function test_envia_nfse_requisicao_invalida(): void
    {
        $nfse = $this->getNFSeValido();
        $url = config('focusnfe.URL.production') . NFSe::URL;

        $this->mockNFSeRequisicaoInvalida($url);

        $response = $this->service->envia($nfse);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('codigo', $response);
        $this->assertEquals('requisicao_invalida', $response['codigo']);
        $this->assertArrayHasKey('mensagem', $response);
    }

    /**
     * Testa consulta de NFSe autorizada
     */
    public function test_consulta_nfse_autorizada(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        $this->mockNFSeAutorizada($url);

        $response = $this->service->get($referencia);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('autorizado', $response['status']);
        $this->assertArrayHasKey('ref', $response);
        $this->assertEquals($referencia, $response['ref']);
    }

    /**
     * Testa consulta de NFSe processando
     */
    public function test_consulta_nfse_processando(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        $this->mockNFSeProcessandoAutorizacao($url);

        $response = $this->service->get($referencia);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('processando_autorizacao', $response['status']);
    }

    /**
     * Testa cancelamento de NFSe com sucesso
     */
    public function test_cancela_nfse_com_sucesso(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        $this->mockNFSeCancelada($url);

        $response = $this->service->cancela($referencia);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('cancelado', $response['status']);
        $this->assertArrayHasKey('caminho_xml_cancelamento', $response);
    }

    /**
     * Testa cancelamento de NFSe com erro
     */
    public function test_cancela_nfse_com_erro(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        $this->mockNFSeErroCancelamento($url);

        $response = $this->service->cancela($referencia);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('erro_cancelamento', $response['status']);
        $this->assertArrayHasKey('erros', $response);
    }

    /**
     * Testa cancelamento de NFSe já cancelada
     */
    public function test_cancela_nfse_ja_cancelada(): void
    {
        $referencia = 'nfs-2';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        $this->mockNFSeJaCancelada($url);

        $response = $this->service->cancela($referencia);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('codigo', $response);
        $this->assertEquals('nfe_cancelada', $response['codigo']);
    }

    /**
     * Testa reenvio de email de NFSe
     */
    public function test_reenvia_email_nfse(): void
    {
        $referencia = 'nfs-2';
        $email = 'teste@example.com';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}/{$email}";

        $this->mockNFSeReenvioEmailSucesso($url);

        $response = $this->service->reenviaEmail($referencia, $email);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('email_reenviado', $response['status']);
    }

    /**
     * Testa se o token de autorização está sendo enviado corretamente
     */
    public function test_envia_token_autorizacao(): void
    {
        $nfse = $this->getNFSeValido();
        $url = config('focusnfe.URL.production') . NFSe::URL;

        Http::fake([
            $url => Http::response(['status' => 'autorizado'], 201)
        ]);

        $this->service->envia($nfse);

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', $this->token);
        });
    }

    /**
     * Testa se a URL está sendo montada corretamente
     */
    public function test_monta_url_corretamente(): void
    {
        $nfse = $this->getNFSeValido();
        $expectedUrl = config('focusnfe.URL.production') . NFSe::URL;

        Http::fake([
            $expectedUrl => Http::response(['status' => 'autorizado'], 201)
        ]);

        $this->service->envia($nfse);

        Http::assertSent(function ($request) use ($expectedUrl) {
            return $request->url() === $expectedUrl;
        });
    }
}
