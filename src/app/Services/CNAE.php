<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por consultar códigos CNAE via API FocusNFe
 */
class CNAE
{
    /**
     * URL base da API CNAE
     */
    public const URL = '/v2/codigos_cnae';

    /**
     * Token de acesso
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
     */
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Lista todos os CNAEs
     */
    public function list(int $offset = 1): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CNAE: Erro ao listar CNAEs', [
                'response' => $response->json(),
                'offset' => $offset
            ]);
        }

        return $response;
    }


    /**
     * Retorna um CNAE pelo código
     *
     * @param string $codigo
     * @return Response
     */
    public function get(string $codigo): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CNAE: Erro ao buscar CNAE', [
                'response' => $response->json(),
                'codigo' => $codigo
            ]);
        }

        return $response;
    }
}
