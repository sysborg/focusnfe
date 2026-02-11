<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFeDTO;

/**
 * Classe responsável por manipular as NFe
 */
class NFe {
    /**
     * URL base da API NFe
     * 
     * @var string
     */
    const URL = '/v2/nfe';

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
     * Envia uma NFe
     *
     * @param NFeDTO $data
     * @param string $referencia
     * @return Response
     */
    public function envia(NFeDTO $data, string $referencia): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref=$referencia", $data->toArray());

        if ($response->failed()) {
            Log::error('FocusNfe.NFe: Erro ao enviar NFe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma NFe
     *
     * @param string $referencia
     * @return Response
     */
    public function get(string $referencia): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            Log::error('FocusNfe.NFe: Erro ao consultar NFe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma NFe
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            Log::error('FocusNfe.NFe: Erro ao cancelar NFe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Cria uma carta de correção
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
            Log::error('FocusNfe.NFe: Erro ao criar carta de correção', [
                'response' => $response->json(),
                'referencia' => $referencia
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
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/inutilizacoes");

        if ($response->failed()) {
            Log::error('FocusNfe.NFe: Erro ao consultar inutilizações', [
                'response' => $response->json()
            ]);
        }

        return $response;
    }
}
