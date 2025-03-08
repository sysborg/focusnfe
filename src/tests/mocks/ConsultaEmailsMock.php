<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\ConsultaEmailsStub;
use Illuminate\Support\Facades\Http;

trait ConsultaEmailsMock {
    /**
     * Simula a resposta da API ao consultar e-mails bloqueados.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(ConsultaEmailsStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(ConsultaEmailsStub::$stub(), $status)
        ]);
    }
}
