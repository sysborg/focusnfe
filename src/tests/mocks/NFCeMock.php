<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFCeStub;
use Illuminate\Support\Facades\Http;

trait NFCeMock
{
    /**
     * Stub para requisição HTTP de conciliação financeira da NFC-e (registro, consulta e cancelamento)
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFCeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFCeStub::$stub(), $status)
        ]);
    }
}
