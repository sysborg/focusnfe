<?php
namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeNacionalStub;
use Illuminate\Support\Facades\Http;

trait NFSeNacionalMock {
  /**
   * Stub de requisição para NFSeNacional
   * 
   * @param string $url
   * @param string $stub
   * @param int $status
   * @param int $times
   * @return void
   */
  public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
  {
    if (!method_exists(NFSeNacionalStub::class, $stub)) {
      throw new \Exception("Stub {$stub} não encontrado");
    }

    Http::fake([
      $url => Http::response(NFSeNacionalStub::$stub(), $status)
    ]);
  }

      /**
     * Simula o envio de NFSe Nacional com sucesso.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalAutorizada(string $url): void
    {
        $this->mockHttp($url, 'autorizada', 200);
    }

    /**
     * Simula a resposta para uma NFSe Nacional que ainda está processando autorização.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalProcessandoEnvio(string $url): void
    {
        $this->mockHttp($url, 'processandoAutorizacaoEnvio', 422);
    }

    /**
     * Simula erro na autorização da NFSe Nacional.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalErroAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'erroAutorizacao', 400);
    }

    /**
     * Simula a resposta para uma NFSe Nacional em processamento ao consultar.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalProcessandoConsulta(string $url): void
    {
        $this->mockHttp($url, 'processandoAutorizacaoConsulta', 422);
    }

    /**
     * Simula o cancelamento de NFSe Nacional autorizado.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalCancelada(string $url): void
    {
        $this->mockHttp($url, 'cancelada', 200);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe Nacional já cancelada.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalJaCancelada(string $url): void
    {
        $this->mockHttp($url, 'nfseJaCancelada', 400);
    }

    /**
     * Simula erro ao tentar cancelar uma NFSe Nacional fora do prazo permitido.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalErroCancelamento(string $url): void
    {
        $this->mockHttp($url, 'erroCancelamento', 400);
    }

    /**
     * Simula erro de requisição inválida ao enviar ou cancelar uma NFSe Nacional.
     *
     * @param string $url
     * @return void
     */
    public function mockNFSeNacionalRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }
}
