<?php
namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\EmpresaStub;
use Illuminate\Support\Facades\Http;

trait EmpresaMock {
  /**
   * Stub de método da empresa
   * 
   * @param string $method
   * @param string $stub
   * @param int $times
   * @return void
   */
  public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
  {
    if (!method_exists(EmpresaStub::class, $stub)) {
      throw new \Exception("Stub {$stub} não encontrado");
    }

    Http::fake([
      $url => Http::response(EmpresaStub::$stub(), $status)
    ]);
  }

  
  /**
     * Simula uma requisição de criação de empresa bem-sucedida.
     *
     * @param string $url
     * @return void
     */
    public function mockEmpresaCriada(string $url): void
    {
        $this->mockHttp($url, 'sucesso', 201);
    }

    /**
     * Simula um erro de validação do certificado ao criar uma empresa.
     *
     * @param string $url
     * @return void
     */
    public function mockErroValidacaoCertificado(string $url): void
    {
        $this->mockHttp($url, 'erroValidacaoCertificado', 400);
    }

    /**
     * Simula um erro de certificado não pertencente ao CNPJ informado.
     *
     * @param string $url
     * @return void
     */
    public function mockErroGeralCertificado(string $url): void
    {
        $this->mockHttp($url, 'erroGeralCertificado', 400);
    }

    /**
     * Simula uma empresa não encontrada na API.
     *
     * @param string $url
     * @return void
     */
    public function mockEmpresaNaoEncontrada(string $url): void
    {
        $this->mockHttp($url, 'erroEmpresaNaoEncontrada', 404);
    }

    /**
     * Simula um erro de permissão negada ao tentar acessar uma empresa.
     *
     * @param string $url
     * @return void
     */
    public function mockErroPermissaoNegada(string $url): void
    {
        $this->mockHttp($url, 'erroPermissaoNegada', 403);
    }

    /**
     * Simula um erro de parâmetros inválidos na requisição.
     *
     * @param string $url
     * @return void
     */
    public function mockErroParametrosInvalidos(string $url): void
    {
        $this->mockHttp($url, 'erroParametrosInvalidos', 400);
    }
}
