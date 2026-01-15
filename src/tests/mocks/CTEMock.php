<?php
namespace Sysborg\FocusNfe\tests\mocks;

use Sysborg\FocusNfe\tests\mocks\Stub\CTeStub;
use Illuminate\Support\Facades\Http;

trait CTeMock {
    /**
     * Mock  para o CTE
     *
     * @param string $url
     * @param string $stub
     * @param int $status
     */
    public function mockHttp(string $url, string $stub, int $status)
    {
        if (!method_exists(CTeStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado em CTeStub.");
        }

        Http::fake([
            $url => Http::response(CTeStub::$stub(), $status)
        ]);
    }

    /**
     * Mock criação do CT-e com sucesso
     */
    public function mockCriacaoCTeSucesso(string $url)
    {
        $this->mockHttp($url, 'consultaCTeSucesso', 201);
    }

    /**
     * Mock de erro na requisição por dados inválidos
     */
    public function mockErroRequisicaoInvalida(string $url)
    {
        $this->mockHttp($url, 'erroRequisicaoInvalida', 400);
    }

    /**
     * Mock para consulta do CT-e autorizado com sucesso
     */
    public function mockConsultaCTeSucesso(string $url)
    {
        $this->mockHttp($url, 'consultaCTeSucesso', 200);
    }

    /**
     * Mock para cancelamento de CT-e autorizado
     */
    public function mockCancelamentoCTeSucesso(string $url)
    {
        $this->mockHttp($url, 'cancelamentoCTeSucesso', 200);
    }

    /**
     * Mock para Carta de Correção Eletrônica (CC-e) do CT-e autorizado
     */
    public function mockCartaCorrecaoCTeSucesso(string $url)
    {
        $this->mockHttp($url, 'cartaCorrecaoCTeSucesso', 200);
    }
}
