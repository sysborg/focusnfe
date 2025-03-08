<?php
namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\EmpresaMock;
use Sysborg\FocusNFe\tests\mocks\Stub\EmpresaStub;
use Sysborg\FocusNFe\Services\Empresas;

class EmpresasControllerTest extends Common {
  use EmpresaMock;

  /**
   * Teste de criação de empresa bem sucessido
   * 
   * @return void
   */
  public function test_criacao_empresa_sucesso(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::sucesso());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL,
      'sucesso',
      200
    );

    $response = $this->post($this->prefix . '/empresas', $inputData);
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
  }

  /**
   * Teste de criação de uma empresa com erro de validação de certificado
   * 
   * @return void
   */
  public function test_criacao_empresa_erro_validacao_certificado(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroValidacaoCertificado());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL,
      'erroValidacaoCertificado',
      422
    );

    $response = $this->post($this->prefix . '/empresas', $inputData);
    $json = $response->json();

    $response->assertStatus(422);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'erro_validacao');
    $response->assertEquals($json['erros']['campo'], 'arquivo_certificado_base64');
  }

  /**
   * Teste de criação de uma empresa com erro de senha ou de cnpj de outra empresa do certificado digital
   * 
   * @return void
   */
  public function test_criacao_empresa_erro_geral(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroGeralCertificado());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL,
      'erroGeralCertificado',
      422
    );

    $response = $this->post($this->prefix . '/empresas', $inputData);
    $json = $response->json();

    $response->assertStatus(422);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'erro_validacao');
    $response->assertEquals($json['erros']['campo'], 'arquivo_certificado_base64');
  }
}