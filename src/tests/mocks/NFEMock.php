<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Sysborg\FocusNfe\tests\mocks\Stub\NFeStub;
use Illuminate\Support\Facades\Http;

trait NFeMock
{
    /**
     * Stub para requisição HTTP de inutilização da NFe
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(NFeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(NFeStub::$stub(), $status)
        ]);
    }



    /**
     * Simula uma requisição de envio de NFe autorizada.
     *
     * @param string $url
     * @return void
     */
    public function mockEnvioNFeSucesso(string $url): void
    {
        $this->mockHttp($url, 'envioNFeSucesso', 200);
    }

    /**
     * Simula um erro na validação do Schema XML da NFe.
     *
     * @param string $url
     * @return void
     */
    public function mockErroValidacaoSchema(string $url): void
    {
        $this->mockHttp($url, 'erroValidacaoSchema', 422);
    }

    /**
     * Simula uma consulta de NFe autorizada.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFeAutorizada(string $url): void
    {
        $this->mockHttp($url, 'consultaNFeAutorizada', 200);
    }

    /**
     * Simula uma consulta de NFe que ainda está sendo processada.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFeProcessando(string $url): void
    {
        $this->mockHttp($url, 'consultaNFeProcessando', 202);
    }

    /**
     * Simula um erro de autorização da NFe.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFeErroAutorizacao(string $url): void
    {
        $this->mockHttp($url, 'consultaNFeErroAutorizacao', 422);
    }

    /**
     * Simula uma consulta de NFe com CC-e anexado.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFeComCCe(string $url): void
    {
        $this->mockHttp($url, 'consultaNFeComCCe', 200);
    }

    /**
     * Simula uma consulta de NFe cancelada.
     *
     * @param string $url
     * @return void
     */
    public function mockConsultaNFeCancelada(string $url): void
    {
        $this->mockHttp($url, 'consultaNFeCancelada', 200);
    }

    /**
     * Simula um cancelamento de NFe autorizado.
     *
     * @param string $url
     * @return void
     */
    public function mockCancelamentoNFeSucesso(string $url): void
    {
        $this->mockHttp($url, 'cancelamentoNFeSucesso', 200);
    }

    /**
     * Simula um erro de cancelamento da NFe (requisição inválida).
     *
     * @param string $url
     * @return void
     */
    public function mockErroCancelamentoNFe(string $url): void
    {
        $this->mockHttp($url, 'erroCancelamentoNFe', 400);
    }

    /**
     * Simula um envio de Carta de Correção (CC-e) autorizado.
     *
     * @param string $url
     * @return void
     */
    public function mockCartaCorrecaoSucesso(string $url): void
    {
        $this->mockHttp($url, 'cartaCorrecaoSucesso', 200);
    }

    /**
     * Simula uma inutilização de numeração de NFe autorizada.
     *
     * @param string $url
     * @return void
     */
    public function mockInutilizacaoNFeSucesso(string $url): void
    {
        $this->mockHttp($url, 'inutilizacaoNFeSucesso', 200);
    }

    /**
     * Simula um erro ao tentar inutilizar uma numeração de NFe.
     *
     * @param string $url
     * @return void
     */
    public function mockInutilizacaoNFeErro(string $url): void
    {
        $this->mockHttp($url, 'inutilizacaoNFeErro', 422);
    }

    
}
