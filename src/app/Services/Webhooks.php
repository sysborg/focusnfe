<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\WebhookDTO;

/**
 * Serviço responsável por gerenciar webhooks via API FocusNFe
 *
 * Permite cadastrar, listar, consultar, atualizar, remover e testar
 * webhooks para receber notificações de eventos fiscais.
 */
class Webhooks
{
    /**
     * URL base da API de Hooks
     *
     * @var string
     */
    public const URL = '/v2/hooks';

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
     * @param string $ambiente
     */
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Cadastra um novo webhook
     *
     * @param WebhookDTO $data
     * @return Response
     */
    public function cadastrar(WebhookDTO $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL,
            $data->toArray()
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao cadastrar webhook', [
                'response' => $response->json(),
                'data' => $data->toArray(),
            ]);
        }

        return $response;
    }

    /**
     * Lista todos os webhooks cadastrados
     *
     * @return Response
     */
    public function listar(): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(
            config('focusnfe.URL.' . $this->ambiente) . self::URL
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao listar webhooks', [
                'response' => $response->json(),
            ]);
        }

        return $response;
    }

    /**
     * Consulta um webhook pelo ID
     *
     * @param int $id
     * @return Response
     */
    public function consultar(int $id): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id"
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao consultar webhook', [
                'response' => $response->json(),
                'id' => $id,
            ]);
        }

        return $response;
    }

    /**
     * Atualiza um webhook existente
     *
     * @param int $id
     * @param WebhookDTO $data
     * @return Response
     */
    public function atualizar(int $id, WebhookDTO $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->put(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id",
            $data->toArray()
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao atualizar webhook', [
                'response' => $response->json(),
                'id' => $id,
                'data' => $data->toArray(),
            ]);
        }

        return $response;
    }

    /**
     * Remove um webhook pelo ID
     *
     * @param int $id
     * @return Response
     */
    public function remover(int $id): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->delete(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id"
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao remover webhook', [
                'response' => $response->json(),
                'id' => $id,
            ]);
        }

        return $response;
    }

    /**
     * Envia um payload de teste para o webhook
     *
     * @param int $id
     * @return Response
     */
    public function testar(int $id): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id/testar"
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.Webhooks: Erro ao testar webhook', [
                'response' => $response->json(),
                'id' => $id,
            ]);
        }

        return $response;
    }
}
