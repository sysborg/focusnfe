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
}
