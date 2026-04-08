<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;
use Sysborg\FocusNfe\app\Events\NFCeAutorizada;
use Sysborg\FocusNfe\app\Events\NFCeCancelada;

/**
 * Classe responsável por manipular as NFCe
 *
 */
class NFCe extends EventHelper
{
    /**
     * URL base da API NFCe
     *
     * @var string
     */
    public const URL = '/v2/nfce';

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
     * Envia uma NFCe
     *
     * @param NFCeDTO $data
     * @return Response
     */
    public function envia(NFCeDTO $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data->toArray());

        $this->dispatch(NFCeAutorizada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao enviar NFCe', $this->ambiente, 'post', $url, $response, [
                'data' => $data->toArray(),
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma NFCe
     *
     * @param string $referencia
     * @return Response
     */
    public function get(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao consultar NFCe', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma NFCe
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        $this->dispatch(NFCeCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao cancelar NFCe', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Consulta numerações inutilizadas
     *
     * @return Response
     */
    public function inutilizacoes(): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/inutilizacoes";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao consultar inutilizações', $this->ambiente, 'get', $url, $response);
        }

        return $response;
    }

    /**
     * Reenvia o email da NFC-e para o destinatário
     *
     * @param string $referencia
     * @param string $email
     * @return Response
     */
    public function reenviaEmail(string $referencia, string $email): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/$email";
        $response = FocusNfeHttp::withToken($this->token)->post($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao reenviar email da NFC-e', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
                'email' => $email,
            ]);
        }

        return $response;
    }

    /**
     * Registra um evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function registraEconf(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf";
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao registrar ECONF', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }


    /**
     * Consulta um evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $protocolo
     * @return Response
     */
    public function consultaEconf(string $referencia, string $protocolo): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao consultar ECONF', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
                'protocolo' => $protocolo,
            ]);
        }

        return $response;
    }


    /**
     * Cancela um evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $protocolo
     * @return Response
     */
    public function cancelaEconf(string $referencia, string $protocolo): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNFe.NFCe: Erro ao cancelar ECONF', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
                'protocolo' => $protocolo,
            ]);
        }

        return $response;
    }
}
