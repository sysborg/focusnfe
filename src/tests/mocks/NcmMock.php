<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NCMStub;
use Illuminate\Support\Facades\Http;

trait NCMMock
{
    /**
     * Stub para requisição de NCM
     *
     * @param string $url
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttpNCM(string $url, int $status, int $times = 1): void
    {
        Http::fake([
            $url => Http::response(NCMStub::consultaNCM(), $status)
        ]);
    }
}
