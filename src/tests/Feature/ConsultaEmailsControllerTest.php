<?php

namespace Sysborg\FocusNFe\tests\Feature;

use Sysborg\FocusNFe\tests\mocks\Stub\ConsultaEmailsStub;
use Sysborg\FocusNFe\app\Services\ConsultaEmails;
use Sysborg\FocusNFe\tests\mocks\ConsultaEmailsMock;

class ConsultaEmailsControllerTest extends Common
{
    use ConsultaEmailsMock;

    /**
     * Teste de consulta de email bloqueado com sucesso.
     *
     * @return void
     */
    public function test_consulta_email_bloqueado(): void
    {
        $email = "teste@exemplo.com";
        $expectedKeys = array_keys(json_decode(ConsultaEmailsStub::emailBloqueado(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . ConsultaEmails::URL . '/' . $email,
            'emailBloqueado',
            200
        );

        $response = $this->get($this->prefix . '/consulta-emails/' . $email);
        $response->assertStatus(200);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de consulta de email não encontrado.
     *
     * @return void
     */
    public function test_consulta_email_nao_encontrado(): void
    {
        $email = "naoexiste@exemplo.com";
        $expectedKeys = array_keys(json_decode(ConsultaEmailsStub::emailNaoEncontrado(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . ConsultaEmails::URL . '/' . $email,
            'emailNaoEncontrado',
            404
        );

        $response = $this->get($this->prefix . '/consulta-emails/' . $email);
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'nao_encontrado');
        $response->assertEquals($json['mensagem'], 'Email não encontrado na lista de bloqueios');
    }

    /**
     * Teste de exclusão de email bloqueado com erro de permissão.
     *
     * @return void
     */
    public function test_exclusao_email_bloqueado_requisicao_invalida(): void
    {
        $email = "teste@exemplo.com";
        $expectedKeys = array_keys(json_decode(ConsultaEmailsStub::requisicaoInvalida(), true));

        $this->mockHttp(
            config('focusnfe.URL.production') . ConsultaEmails::URL . '/' . $email,
            'requisicaoInvalida',
            400
        );

        $response = $this->delete($this->prefix . '/consulta-emails/' . $email);
        $json = $response->json();

        $response->assertStatus(400);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['codigo'], 'requisicao_invalida');
        $response->assertEquals($json['mensagem'], 'Email bloqueado por motivo de reclamação não pode ser excluído. Entre em contato com o suporte.');
    }
}
