<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class ConsultaEmailsStub {
   
    /**
     * Retorna uma resposta simulada de um e-mail bloqueado.
     *
     * @return string
     */
    public static function emailBloqueado(): string
    {
        return json_encode([
            "email" => "teste@exemplo.com",
            "block_type" => "bounce",
            "bounce_type" => "transient",
            "diagnostic_code" => "smtp; 554 4.4.7 Message expired: unable to deliver in 1440 minutes.<421 4.4.0 Unable to lookup DNS for example.com>",
            "blocked_at" => "2020-04-16T12:24:29-03:00"
        ]);
    }

    /**
     * Retorna uma resposta simulada para e-mail não encontrado na lista de bloqueios.
     *
     * @return string
     */
    public static function emailNaoEncontrado(): string
    {
        return json_encode([
            "codigo" => "nao_encontrado",
            "mensagem" => "Email não encontrado na lista de bloqueios"
        ]);
    }

      /**
     * Retorna uma resposta simulada para solicitação não atendida ao tentar excluir um e-mail bloqueado.
     *
     * @return string
     */
    public static function requisicaoInvalida(): string
    {
        return json_encode([
            "codigo" => "requisicao_invalida",
            "mensagem" => "Email bloqueado por motivo de reclamação não pode ser excluído. Entre em contato com o suporte."
        ]);
    }

}
