<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por manipular os backups via API FocusNFe
 */
class Backups
{
    /**
     * URL base da API Backups
     *
     * @var string
     */
    public const URL = '/v2/backups/%s.json';

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
     * Pega os backups de um determinado CNPJ
     *
     * @param string $cnpj
     * @return Response
     */
    public function get(string $cnpj): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . sprintf(self::URL, $cnpj));

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.Backups: Erro ao consultar backup do CNPJ', [
              'response' => $response->json(),
              'cnpj' => $cnpj
            ]);
        }

        return $response;
    }
}
