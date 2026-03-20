<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por consultar CNPJs via API FocusNFe
 */
class Cnpjs
{
    /**
     * URL base da API CNPJS
     *
     * @var string
     */
    public const URL = '/v2/cnpjs';

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
     * Pega um cnpj por sua numeração
     *
     * @param string $cnpj
     * @return Response
     */
    public function get(string $cnpj): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$cnpj");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.Cnpjs: Erro ao consultar CNPJ', [
            'response' => $response->json(),
            'data' => [
                'cnpj' => $cnpj
            ]
            ]);
        }

        return $response;
    }
}
