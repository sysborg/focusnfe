<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\Events\NFSeEnviada;
use Sysborg\FocusNfe\app\Events\NFSeCancelada;
use Illuminate\Http\Client\Response;

/**
 * Classe responsável por manipular as NFSe
 * https://focusnfe.com.br/doc/?php#nfse
 */

class NFSe extends EventHelper
{
    /**
     * URL base da API NFSe
     *
     * @var string
     */
    public const URL = '/v2/nfse';

    /**
     * Ambiente de produção ou sandbox
     *
     * @var string
     */
    private string $ambiente;

    /**
     * Token de acesso
     *
     * @var string
     */
    private string $token;

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
     * Envia uma NFSe
     *
     * @param NFSeDTO $data
     * @param string $ref - Número interno da NFSe para referência
     * @return array
     */
    public function envia(NFSeDTO $data, string $ref): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . '?ref=' . $ref;
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data->toArray());

        if (!($response instanceof Response)) {
            FocusNfeLogger::error('FocusNfe.NFSe: Resposta inválida ao enviar NFSe', [
              'data' => $data->toArray(),
              'response' => $response
            ]);

            throw new \Exception('Resposta inválida ao enviar NFSe: ' . print_r($response, true));
        }

        FocusNfeLogger::debug('FocusNfe.NFSe: Enviando NFSe', [
          'ambiente' => $this->ambiente,
          'method' => 'POST',
          'url' => $url,
          'data' => $data->toArray(),
          'response' => $response
        ]);

        $this->dispatch(NFSeEnviada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSe: Erro ao enviar NFSe', $this->ambiente, 'post', $url, $response, [
                'data' => $data->toArray(),
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma NFSe
     *
     * @param string $referencia
     * @return array
     */
    public function get(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSe: Erro ao consultar NFSe', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Cancelamento de uma NFSe
     *
     * @param string $referencia
     * @return array
     */
    public function cancela(string $referencia, string $justificativa): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->delete(
            $url,
            ['justificativa' => $justificativa]
        );

        $this->dispatch(NFSeCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSe: Erro ao cancelar NFSe', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
                'justificativa' => $justificativa,
            ]);
        }

        return $response;
    }

    /**
     * Reenvia email da NFSe
     *
     * @param string $referencia
     * @param string $email
     * @return array
     */
    public function reenviaEmail(string $referencia, string $email): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/$email";
        $response = FocusNfeHttp::withToken($this->token)->post($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSe: Erro ao reenviar email da NFSe', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
                'email' => $email,
            ]);
        }

        return $response;
    }
}
