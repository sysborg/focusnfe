<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\CTERecebidasStub;
use Illuminate\Support\Facades\Http;

trait CTERecebidasMock {
  /**
   * Stub de método para CTE Recebidas
   * 
   * @param string $method
   * @param string $stub
   * @param int $times
   * @return void
   */
  public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
  {
    if (!method_exists(CTERecebidasStub::class, $stub)) {
      throw new \Exception("Stub {$stub} não encontrado");
    }

    Http::fake([
      $url => Http::response(CTERecebidasStub::$stub(), $status)
    ]);
  }

  /**
   * Simula a consulta de CTEs recebidas.
   *
   * @param string $url
   * @return void
   */
  public function mockConsultaCTEsRecebidas(string $url): void
  {
      $this->mockHttp($url, 'consultaCTesRecebidas', 200);
  }

  /**
   * Simula a consulta individual de uma CTE recebida.
   *
   * @param string $url
   * @return void
   */
  public function mockConsultaIndividual(string $url): void
  {
      $this->mockHttp($url, 'consultaIndividual', 200);
  }

  /**
   * Simula a informação de desacordo de uma CTE recebida.
   *
   * @param string $url
   * @return void
   */
  public function mockInformarDesacordo(string $url): void
  {
      $this->mockHttp($url, 'informarDesacordo', 200);
  }

  /**
   * Simula a consulta de desacordo já registrado.
   *
   * @param string $url
   * @return void
   */
  public function mockConsultaDesacordo(string $url): void
  {
      $this->mockHttp($url, 'consultaDesacordo', 200);
  }
}
