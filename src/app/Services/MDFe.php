<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\Events\MDFeAutorizado;
use Sysborg\FocusNfe\app\Events\MDFeCancelado;
use Sysborg\FocusNfe\app\Events\MDFeEncerrado;

/**
 * Classe responsável por manipular o MDF-e
 *
 */
class MDFe extends EventHelper
{
    /**
     * URL base da API MDF-e
     *
     * @var string
     */
    public const URL = '/v2/mdfe';

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
     * Envia um MDF-e para processamento
     *
     * @param array $data
     * @return Response
     */
    public function envia(array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data);

        $this->dispatch(MDFeAutorizado::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao enviar MDF-e', [
                'response' => $response->json(),
                'data' => $data
            ]);
        }

        return $response;
    }

    /**
     * Consulta um MDF-e pelo ID
     *
     * @param string $referencia
     * @return Response
     */
    public function consulta(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao consultar MDF-e', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Cancela um MDF-e
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        $this->dispatch(MDFeCancelado::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao cancelar MDF-e', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Inclui um condutor no MDF-e
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function incluiCondutor(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/inclusao_condutor", $data);

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao incluir condutor no MDF-e', [
                'response' => $response->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $response;
    }

    /**
     * Inclui um DFe no MDF-e
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function incluiDFe(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/inclusao_dfe", $data);

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao incluir DFe no MDF-e', [
                'response' => $response->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $response;
    }

    /**
     * Encerra um MDF-e
     *
     * @param string $referencia
     * @return Response
     */
    public function encerra(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/encerrar");

        $this->dispatch(MDFeEncerrado::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.MDFe: Erro ao encerrar MDF-e', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }
}
