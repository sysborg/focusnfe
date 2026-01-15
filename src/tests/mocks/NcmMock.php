<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Sysborg\FocusNfe\tests\mocks\Stub\NCMStub;
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
    public function mockHttp(string $url, int $status, int $times = 1): void
    {
        Http::fake([
            $url => Http::response(NCMStub::consultaNCM(), $status)
        ]);
    }

     /**
     * Simula a consulta de todos os NCMs.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNCM(string $url): void
    {
        $this->mockHttp($url, 'consultaNCM', 200);
    }

    
}
