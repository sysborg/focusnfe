<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por consultar CEP via API FocusNFe
 */
class CEP
{
    /**
     * URL base da API CEP
     *
     * @var string
     */
    public const URL = '/v2/ceps';

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
     * Pega um cep por sua numeração
     *
     * @param string $cep
     * @return Response
     */
    public function get(string $cep): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$cep";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Cep: Erro ao consultar CEP', $this->ambiente, 'get', $url, $response, [
                'data' => [
                    'cep' => $cep,
                ],
            ]);
        }

        return $response;
    }
}
