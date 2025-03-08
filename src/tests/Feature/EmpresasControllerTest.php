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

  /**
   * Testa alteração de uma empresa com sucesso
   * 
   * @return void
   */
  public function test_alteracao_empresa_sucesso(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::sucesso());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'sucesso',
      200
    );

    $response = $this->put($this->prefix . '/empresas/' . $inputData['cnpj'], $inputData);
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
  }

  /**
   * Testa alteração de uma empresa com erro de validação de certificado
   * 
   * @return void
   */
  public function test_alteracao_empresa_erro_validacao_certificado(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroValidacaoCertificado());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'erroValidacaoCertificado',
      422
    );

    $response = $this->put($this->prefix . '/empresas/' . $inputData['cnpj'], $inputData);
    $json = $response->json();

    $response->assertStatus(422);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'erro_validacao');
    $response->assertEquals($json['erros']['campo'], 'arquivo_certificado_base64');
  }

  /**
   * Testa alteração de uma empresa com erro de senha ou de cnpj de outra empresa do certificado digital
   * 
   * @return void
   */
  public function test_alteracao_empresa_erro_geral(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroGeralCertificado());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'erroGeralCertificado',
      422
    );

    $response = $this->put($this->prefix . '/empresas/' . $inputData['cnpj'], $inputData);
    $json = $response->json();

    $response->assertStatus(422);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'erro_validacao');
    $response->assertEquals($json['erros']['campo'], 'arquivo_certificado_base64');
  }

  /**
   * Testa exclusão de uma empresa com sucesso
   * 
   * @return void
   */
  public function test_exclusao_empresa_sucesso(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::sucesso());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'sucesso',
      200
    );

    $response = $this->delete($this->prefix . '/empresas/' . $inputData['cnpj']);
    $response->assertStatus(200);
    $response->assertJsonStructure($expectedKeys);
  }

  /**
   * Testa exclusão de empresa não encontrada
   * 
   * @return void
   */
  public function test_exclusao_empresa_nao_encontrada(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroEmpresaNaoEncontrada());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'erroNaoEncontrado',
      404
    );

    $response = $this->delete($this->prefix . '/empresas/' . $inputData['cnpj']);
    $json = $response->json();

    $response->assertStatus(404);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'nao_encontrado');
  }

  /**
   * Teste de exclusão de empresa com permissão negada
   * 
   * @return void
   */
  public function test_exclusao_empresa_permissao_negada(): void
  {
    $inputData = EmpresaStub::request();
    $expectedKeys = array_keys(EmpresaStub::erroPermissaoNegada());
    $this->mockHttp(
      config('focusnfe.URL.production') . Empresas::URL . '/' . $inputData['cnpj'],
      'erroPermissaoNegada',
      403
    );

    $response = $this->delete($this->prefix . '/empresas/' . $inputData['cnpj']);
    $json = $response->json();

    $response->assertStatus(403);
    $response->assertJsonStructure($expectedKeys);
    $response->assertEquals($json['erros']['codigo'], 'permissao_negada');
  }
}
