<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeRecebidasStub;
use Illuminate\Support\Facades\Http;

trait NFSeRecebidasMock {

    /**
     * Stub para NFSeRecebidas.
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeRecebidasStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFSeRecebidasStub::$stub(), $status)
        ]);
    }
}
