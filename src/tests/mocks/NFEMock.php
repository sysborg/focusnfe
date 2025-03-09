<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFeStub;
use Illuminate\Support\Facades\Http;

trait NFeMock
{
    /**
     * Stub para requisição HTTP de inutilização da NFe
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFeStub::$stub(), $status)
        ]);
    }
}
