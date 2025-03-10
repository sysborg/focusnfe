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

        /**
     * Simula a emissão bem-sucedida de uma NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockEmissaoNFCe(string $url): void
    {
        $this->mockHttp($url, 'consultaNFCe', 200);
    }

    /**
     * Simula um erro de autorização na emissão da NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockErroAutorizacaoNFCe(string $url): void
    {
        $this->mockHttp($url, 'erroAutorizacao', 400);
    }

    /**
     * Simula uma NFC-e cancelada.
     *
     * @param string $url
     * @return void
     */
    public function mockCancelamentoNFCe(string $url): void
    {
        $this->mockHttp($url, 'cancelamentoNFCe', 200);
    }

    /**
     * Simula um erro ao tentar cancelar uma NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockErroCancelamentoNFCe(string $url): void
    {
        $this->mockHttp($url, 'erroCancelamentoNFCe', 400);
    }

    /**
     * Simula um erro ao consultar uma NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockErroConsultaNFCe(string $url): void
    {
        $this->mockHttp($url, 'erroConsultaNFCe', 404);
    }

    /**
     * Simula a inutilização bem-sucedida de uma NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockInutilizacaoNFCe(string $url): void
    {
        $this->mockHttp($url, 'inutilizacaoNFCe', 200);
    }

    /**
     * Simula um erro ao tentar inutilizar uma NFC-e.
     *
     * @param string $url
     * @return void
     */
    public function mockErroInutilizacaoNFCe(string $url): void
    {
        $this->mockHttp($url, 'erroInutilizacaoNFCe', 400);
    }
}
