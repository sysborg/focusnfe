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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;
        $response = FocusNfeHttp::withToken($this->token)->post(
            $url,
            $data->toArray()
        );

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao cadastrar webhook', $this->ambiente, 'post', $url, $response, [
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao listar webhooks', $this->ambiente, 'get', $url, $response);
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao consultar webhook', $this->ambiente, 'get', $url, $response, [
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->put(
            $url,
            $data->toArray()
        );

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao atualizar webhook', $this->ambiente, 'put', $url, $response, [
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao remover webhook', $this->ambiente, 'delete', $url, $response, [
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id/testar";
        $response = FocusNfeHttp::withToken($this->token)->post($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.Webhooks: Erro ao testar webhook', $this->ambiente, 'post', $url, $response, [
                'id' => $id,
            ]);
        }

        return $response;
    }
}
