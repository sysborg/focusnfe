<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\CEPStub;
use Illuminate\Support\Facades\Http;

trait CEPMock {
    /**
     * Simula uma resposta da API de CEPs com base em um stub específico.
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     * @throws \Exception
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(CEPStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado na classe CEPStub.");
        }

        Http::fake([
            $url => Http::response(CEPStub::$stub(), $status)
        ]);
    }
}
