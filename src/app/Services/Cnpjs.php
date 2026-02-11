<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class Cnpjs {
    /**
     * URL base da API CNPJS
     * 
     * @var string
     */
    const URL = '/v2/cnpjs';

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
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente ) . self::URL . "/$cnpj");

        if ($response->failed()) {
            Log::error('FocusNfe.Cnpjs: Erro ao consultar CNPJ', [
            'response' => $response->json(),
            'data' => [
                'cnpj' => $cnpj
            ]
            ]);
        }

        return $response;
    }
}
