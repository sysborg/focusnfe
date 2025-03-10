<?php

namespace Sysborg\FocusNFe\tests\mocks;

use Sysborg\FocusNFe\tests\mocks\Stub\NFSeRecebidasStub;
use Illuminate\Support\Facades\Http;

trait NFSeRecebidasMock {

    /**
     * Stub para NFSeRecebidas.
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFSeRecebidasStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFSeRecebidasStub::$stub(), $status)
        ]);
    }


    /**
     * Simula a busca por todas as NFSe recebidas de um CNPJ.
     *
     * @param string $url
     * @return void
     */
    public function mockTodasNFSeRecebidas(string $url): void
    {
        $this->mockHttp($url, 'todasNfseRecebidas', 200);
    }

    /**
     * Simula a consulta de uma NFSe específica pela chave.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFSePorChave(string $url): void
    {
        $this->mockHttp($url, 'consultaNfsePorChave', 200);
    }

    /**
     * Simula um erro quando a NFSe não for encontrada pela chave.
     *
     * @param string $url
     * @return void
     */
    public function mockErroChaveNaoEncontrada(string $url): void
    {
        $this->mockHttp($url, 'erroChaveNaoEncontrada', 404);
    }
}
