<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\MDFeStub;
use Illuminate\Support\Facades\Http;

trait MDFEMock
{

    /**
     * Stub para requisição de cancelamento de MDF-e
     *
     * @param string $url
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(MDFeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(MDFeStub::$stub(), $status)
        ]);
    }
}
