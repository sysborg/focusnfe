<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CNAE
{
    /**
     * URL base da API CNAE
     */
    const URL = '/v2/codigos_cnae';

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
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset");

        if ($response->failed()) {
            Log::error('FocusNFe.CNAE: Erro ao listar CNAEs', [
                'response' => $response->json(),
                'offset' => $offset
            ]);
        }

        return $response;
    }


    public function get(string $codigo): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

        if ($response->failed()) {
            Log::error('FocusNFe.CNAE: Erro ao buscar CNAE', [
                'response' => $response->json(),
                'codigo' => $codigo
            ]);
        }

        return $response;
    }
}