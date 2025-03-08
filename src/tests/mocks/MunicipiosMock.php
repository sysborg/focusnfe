<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\MunicipioStub;
use Illuminate\Support\Facades\Http;

trait MunicipioMock
{
    /**
     * Stub para requisição de municípios
     *
     * @param string $url
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, int $status, int $times = 1): void
    {
        Http::fake([
            $url => Http::response(MunicipioStub::consultaMunicipios(), $status)
        ]);
    }
}
