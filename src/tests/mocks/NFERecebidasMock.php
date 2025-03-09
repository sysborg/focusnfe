<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\tests\mocks\Stub\NFERecebidasStub;

trait NFeRecebidasMock {
    /**
     * Simula a resposta da API para uma manifestação de NFe Recebida.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFERecebidasStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFERecebidasStub::$stub(), $status)
        ]);
    }
}
