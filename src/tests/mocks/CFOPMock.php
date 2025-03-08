<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\CFOPStub;
use Illuminate\Support\Facades\Http;

trait CFOPMock {
    
    /**
     * Simula uma requisição HTTP para CFOP.
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(CFOPStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(CFOPStub::$stub(), $status)
        ]);
    }
}
