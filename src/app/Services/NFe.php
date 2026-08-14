<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\DTO\NFeEmissaoResponseDTO;
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
     * Envia uma NF-e e retorna o contrato tipado do endpoint de emissão.
     *
     * Cobre os status documentados: 201, 202, 400, 401, 415 e 422.
     *
     * @param NFeDTO $data
     * @param string $referencia
     * @return NFeEmissaoResponseDTO
     */
    public function enviaDto(NFeDTO $data, string $referencia): NFeEmissaoResponseDTO
    {
        return NFeEmissaoResponseDTO::fromResponse($this->envia($data, $referencia));
    }

    /**
     * Consulta uma NF-e pelo número de referência
     *
     * @param string $referencia
     * @return Response
     */
    public function get(string $referencia, bool $completa = false): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->get(
            $url,
            $completa ? ['completa' => 1] : []
        );

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao consultar NF-e', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma NF-e.
     *
     * A API Focus NFe exige justificativa entre 15 e 255 caracteres. Uma string
     * sera convertida para ['justificativa' => $valor].
     *
     * @param string $referencia
     * @param array<mixed>|string $data
     * @return Response
     */
    public function cancela(string $referencia, array|string $data = []): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $payload = is_string($data) ? ['justificativa' => $data] : $data;
        $response = FocusNfeHttp::withToken($this->token)->delete($url, $payload);

        $this->dispatch(NFeCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao cancelar NF-e', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Importa uma NF-e a partir do XML.
     *
     * @param string $xml Conteudo XML da NF-e
     * @param string|null $referencia Referencia unica opcional; se omitida, a API usa a chave da nota
     * @return Response
     */
    public function importaXml(string $xml, ?string $referencia = null): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . '/importacao';
        if ($referencia !== null) {
            $url .= '?ref=' . urlencode($referencia);
        }

        $response = FocusNfeHttp::withToken($this->token)
            ->pending()
            ->withBody($xml, 'application/xml')
            ->post($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao importar XML da NF-e', $this->ambiente, 'post', $url, $response, [
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
     * Consulta numeracoes inutilizadas.
     *
     * @param array<mixed> $filtros Filtros oficiais: cnpj, cpf, data_recebimento_inicial,
     * data_recebimento_final, numero_inicial, numero_final
     * @return Response
     */
    public function inutilizacoes(array $filtros = []): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . '/inutilizacoes';
        $response = FocusNfeHttp::withToken($this->token)->get($url, $filtros);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao consultar inutilizações', $this->ambiente, 'get', $url, $response);
        }

        return $response;
    }

    /**
     * Reenvia o email da NF-e para o destinatário
     *
     * @param string $referencia
     * @param string|array<int, string> $emails
     * @return Response
     */
    public function reenviaEmail(string $referencia, string|array $emails): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/email";
        $emailList = is_array($emails) ? $emails : [$emails];
        $response = FocusNfeHttp::withToken($this->token)->post($url, [
            'emails' => $emailList,
        ]);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao reenviar email da NF-e', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
                'emails' => $emailList,
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
            ['completa' => 1]
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
     * Registra evento de insucesso na entrega da NF-e.
     *
     * @param string $referencia
     * @param array $data Payload oficial com data_tentativa_entrega, numero_tentativas,
     * motivo_insucesso, justificativa_insucesso, latitude_entrega, longitude_entrega,
     * hash_tentativa_entrega e data_hash_tentativa.
     * @return Response
     */
    public function insucessoEntrega(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/insucesso_entrega";
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao registrar insucesso de entrega', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Cancela evento de insucesso na entrega da NF-e.
     *
     * @param string $referencia
     * @return Response
     */
    public function cancelaInsucessoEntrega(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/insucesso_entrega";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao cancelar insucesso de entrega', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Registra um ator interessado na NF-e.
     *
     * @param string $referencia
     * @param array $data Payload oficial com cpf ou cnpj e permite_autorizacao_terceiros
     * @return Response
     */
    public function atorInteressado(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/ator_interessado";
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao registrar ator interessado', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Emite um evento generico de NF-e.
     *
     * @param string $referencia
     * @param array<mixed> $data
     * @return Response
     */
    public function evento(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/evento";
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao registrar evento', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
                'tipo_evento' => $data['tipo_evento'] ?? null,
            ]);
        }

        return $response;
    }

    /**
     * Cancela um evento generico de NF-e.
     *
     * @param string $referencia
     * @param array<mixed>|string $data Tipo do evento ou payload oficial com tipo_evento
     * @return Response
     */
    public function cancelaEvento(string $referencia, array|string $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/evento";
        $payload = is_string($data) ? ['tipo_evento' => $data] : $data;
        $response = FocusNfeHttp::withToken($this->token)->delete($url, $payload);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao cancelar evento', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
                'tipo_evento' => $payload['tipo_evento'] ?? null,
            ]);
        }

        return $response;
    }

    /**
     * Registra prorrogacao de prazo de ICMS suspenso pelo endpoint oficial de eventos.
     *
     * @param string $referencia
     * @param array<mixed> $data
     * @return Response
     */
    public function prorrogacaoIcms(string $referencia, array $data): Response
    {
        return $this->evento($referencia, array_merge([
            'tipo_evento' => 'prorrogacao_suspensao_icms',
        ], $data));
    }

    /**
     * Registra evento de Conciliação Financeira - ECONF.
     *
     * @param string $referencia
     * @param array<mixed> $data Payload oficial com detalhes_pagamento
     * @return Response
     */
    public function registraEconf(string $referencia, array $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf";
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao registrar ECONF', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }

    /**
     * Consulta evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $numeroProtocolo
     * @return Response
     */
    public function consultaEconf(string $referencia, string $numeroProtocolo): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$numeroProtocolo";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao consultar ECONF', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
                'numero_protocolo' => $numeroProtocolo,
            ]);
        }

        return $response;
    }

    /**
     * Cancela evento de Conciliação Financeira - ECONF
     *
     * @param string $referencia
     * @param string $numeroProtocolo
     * @return Response
     */
    public function cancelaEconf(string $referencia, string $numeroProtocolo): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$numeroProtocolo";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao cancelar ECONF', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
                'numero_protocolo' => $numeroProtocolo,
            ]);
        }

        return $response;
    }

    /**
     * Solicita reenvio dos webhooks da NF-e.
     *
     * @param string $referencia
     * @return Response
     */
    public function reenviarHook(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/hook";
        $response = FocusNfeHttp::withToken($this->token)->post($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFe: Erro ao reenviar hook da NF-e', $this->ambiente, 'post', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }
}
