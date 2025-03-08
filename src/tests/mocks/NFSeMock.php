<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeStub;
use Illuminate\Support\Facades\Http;

trait NFSeMock {
    /**
     * Stub de requisições HTTP para NFSe.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado na classe NFSeStub");
        }

        Http::fake([
            $url => Http::response(NFSeStub::$stub(), $status)
        ]);
    }
}
