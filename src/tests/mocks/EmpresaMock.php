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
}
