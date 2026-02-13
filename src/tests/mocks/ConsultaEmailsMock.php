<?php

namespace Sysborg\FocusNfe\tests\mocks;

use Sysborg\FocusNfe\tests\mocks\Stub\ConsultaEmailsStub;
use Illuminate\Support\Facades\Http;

trait ConsultaEmailsMock {
    /**
     * Simula a resposta da API ao consultar e-mails bloqueados.
     * 
     * @param string $url
     * @param string $stub
     * @param int $status
     * @param int $times
     * @return void
     */
    public function mockHttp(string $url, string $stub, int $status, int $times = 1): void
    {
        if (!method_exists(ConsultaEmailsStub::class, $stub)) {
            throw new \Exception("Stub {$stub} não encontrado");
        }

        Http::fake([
            $url => Http::response(ConsultaEmailsStub::$stub(), $status)
        ]);
    }

    /**
     * Simula a consulta de um e-mail bloqueado.
     *
     * @param string $url
     * @return void
     */
    public function mockEmailBloqueado(string $url): void
    {
        $this->mockHttp($url, 'emailBloqueado', 200);
    }

    /**
     * Simula a consulta de um e-mail não encontrado na lista de bloqueios.
     *
     * @param string $url
     * @return void
     */
    public function mockEmailNaoEncontrado(string $url): void
    {
        $this->mockHttp($url, 'emailNaoEncontrado', 404);
    }

    /**
     * Simula um erro ao tentar excluir um e-mail bloqueado por reclamação.
     *
     * @param string $url
     * @return void
     */
    public function mockRequisicaoInvalida(string $url): void
    {
        $this->mockHttp($url, 'requisicaoInvalida', 400);
    }
}
