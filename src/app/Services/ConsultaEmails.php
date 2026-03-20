<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por consultar e gerenciar e-mails bloqueados via API FocusNFe
 */
class ConsultaEmails
{
    /**
     * URL base da API ConsultaEmails
     *
     * @var string
     */
    public const URL = '/v2/blocked_emails';

    /**
     * Token de acesso
     *
     * @var string
     */
    private string $token;

    /**
     * Ambiente de produção ou sandbox
     *
     * @var string
     */
    private string $ambiente;

    /**
     * Construtor da classe
     *
     * @param string $token
     * @return void
     */
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Realiza a consulta de um e-mail
     *
     * @param string $email
     * @return Response
     */
    public function get(string $email): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$email");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.ConsultarEmails: Erro ao consultar e-mail', [
                'response' => $response->json(),
                'data' => ['email' => $email]
            ]);
        }

        return $response;
    }

    /**
     * Realiza a deleção de um e-mail
     *
     * @param string $email
     * @return Response
     */
    public function delete(string $email): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$email");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.ConsultarEmails: Erro ao deletar e-mail', [
                'response' => $response->json(),
                'email' => $email
            ]);
        }

        return $response;
    }
}
