<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\Events\NFeAutorizada;
use Sysborg\FocusNfe\app\Events\NFeCancelada;
use Sysborg\FocusNfe\app\Events\NFeInutilizada;

/**
 * Classe responsável por manipular as NF-e via API FocusNFe v2
 */
class NFe extends EventHelper
{
    /**
     * URL base da API NF-e
     *
     * @var string
     */
    public const URL = '/v2/nfe';

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
     * Envia uma NF-e para autorização
     *
     * @param NFeDTO $data
     * @param string $referencia
     * @return Response
     */
    public function envia(NFeDTO $data, string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref=$referencia";
        $response = FocusNfeHttp::withToken($this->token)->post(
            $url,
            $data->toArray()
        );

        $this->dispatch(NFeAutorizada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao enviar NF-e', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma NF-e pelo número de referência
     *
     * @param string $referencia
     * @return Response
     */
    public function get(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao consultar NF-e', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma NF-e
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        $this->dispatch(NFeCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao cancelar NF-e', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Emite uma Carta de Correção Eletrônica para a NF-e
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function cartaCorrecao(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/carta_correcao";
        $response = FocusNfeHttp::withToken($this->token)->post(
            $url,
            $data
        );

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao criar Carta de Correção', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Envia uma inutilizacao de faixa de numeracao para a NF-e.
     *
     * @param array $data
     * @return Response
     */
    public function inutilizar(array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . '/inutilizacao';
        $response = FocusNfeHttp::withToken($this->token)->post(
            $url,
            $data
        );

        $this->dispatch(NFeInutilizada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao inutilizar faixa de numeracao', $this->ambiente, 'post', $url, $response, [
                'data' => $data,
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . '/inutilizacoes';
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao consultar inutilizações', $this->ambiente, 'get', $url, $response);
        }

        return $response;
    }

    /**
     * Reenvia o email da NF-e para o destinatário
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
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao reenviar email da NF-e', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
                'email' => $email,
            ]);
        }

        return $response;
    }

    /**
     * Faz o download do XML completo da NF-e.
     *
     * @param string $referencia
     * @return Response
     */
    public function downloadXml(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia",
            ['completo' => 'true']
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao baixar XML da NF-e', [
                'response' => $response->json(),
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Registra evento de insucesso na entrega da NF-e
     *
     * @param string $referencia
     * @param array $data {data_tentativa, numero_tentativas, motivo: 0=desconhecido, 1=recusada, 2=inacessível}
     * @return Response
     */
    public function insucessoEntrega(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/insucesso_entrega",
            $data
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao registrar insucesso de entrega', [
                'response' => $response->json(),
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Registra um ator interessado na NF-e (ex: transportador)
     *
     * @param string $referencia
     * @param array $data {tipo_ator: 1=transportador|2=redespacho, cnpj ou cpf, ie}
     * @return Response
     */
    public function atorInteressado(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/ator_interessado",
            $data
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao registrar ator interessado', [
                'response' => $response->json(),
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Registra prorrogação de prazo de ICMS suspenso
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function prorrogacaoIcms(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/prorrogacao_icms",
            $data
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao registrar prorrogação de ICMS', [
                'response' => $response->json(),
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Registra evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function registraEconf(string $referencia, array $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf",
            $data
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao registrar ECONF', [
                'response' => $response->json(),
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Consulta evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $protocolo
     * @return Response
     */
    public function consultaEconf(string $referencia, string $protocolo): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo"
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao consultar ECONF', [
                'response' => $response->json(),
                'referencia' => $referencia,
                'protocolo' => $protocolo,
            ]);
        }

        return $response;
    }

    /**
     * Cancela evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $protocolo
     * @return Response
     */
    public function cancelaEconf(string $referencia, string $protocolo): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->delete(
            config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo"
        );

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFe: Erro ao cancelar ECONF', [
                'response' => $response->json(),
                'referencia' => $referencia,
                'protocolo' => $protocolo,
            ]);
        }

        return $response;
    }
}
