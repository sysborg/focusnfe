<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeArquivoStub;
use Illuminate\Support\Facades\Http;

trait NFSeArquivoMock
{
    /**
     * Stub para requisição HTTP de envio e consulta de NFSe por arquivo
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeArquivoStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFSeArquivoStub::$stub(), $status)
        ]);
    }
}
