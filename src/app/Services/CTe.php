<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CTe {
    /**
     * URL base da API CTe
     *
     * @var string
     */
    const URL = '/v2/cte';

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
     */
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Envia uma CTe
     *
     * @param array $data
     * @param string $referencia
     * @return Response
     */
    public function envia(array $data, string $referencia): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref=$referencia", $data);

        if ($response->failed()) {
            Log::error('FocusNFe.CTe: Erro ao enviar CTe', [
                'response' => $response->json(),
                'data' => $data,
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma CTe
     *
     * @param string $referencia
     * @return Response
     */
    public function consulta(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get($url);

        if ($response->failed()) {
            Log::error('FocusNFe.CTe: Erro ao consultar CTe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma CTe
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->delete($url);

        if ($response->failed()) {
            Log::error('FocusNFe.CTe: Erro ao cancelar CTe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

     /**
     * Cria uma Carta de Correção para uma CTe
     *
     * @param string $referencia
     * @param array $data
     * @return Response
     */
    public function cartaCorrecao(string $referencia, array $data): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/carta_correcao", $data);

        if ($response->failed()) {
            Log::error('FocusNFe.CTe: Erro ao enviar Carta de Correção', [
                'response' => $response->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $response;
    }
}
