<?php

namespace Sysborg\FocusNFe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\app\DTO\NFeDTO;

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
     * Construtor da classe
     * 
     * @param string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Envia uma NFe
     * 
     * @param NFeDTO $data
     * @param string $referencia
     * @return array
     */
    public function envia(NFeDTO $data, string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post(config('focusnfe.URL.production') . self::URL . "?ref=$referencia", $data->toArray());

        if ($request->failed()) {
            Log::error('FocusNFe.NFe: Erro ao enviar NFe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Consulta uma NFe
     * 
     * @param string $referencia
     * @return array
     */
    public function get(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => $this->token,
        ])->get(config('focusnfe.URL.production') . self::URL . "/$referencia");

        if ($request->failed()) {
            Log::error('FocusNFe.NFe: Erro ao consultar NFe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Cancela uma NFe
     * 
     * @param string $referencia
     * @return array
     */
    public function cancela(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => $this->token,
        ])->delete(config('focusnfe.URL.production') . self::URL . "/$referencia");

        if ($request->failed()) {
            Log::error('FocusNFe.NFe: Erro ao cancelar NFe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Cria uma carta de correção
     * 
     * @param string $referencia
     * @param array $data
     * @return array
     */
    public function cartaCorrecao(string $referencia, array $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post(config('focusnfe.URL.production') . self::URL . "/$referencia/carta_correcao", $data);

        if ($request->failed()) {
            Log::error('FocusNFe.NFe: Erro ao criar carta de correção', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Consulta numerações inutilizadas
     * 
     * @return array
     */
    public function inutilizacoes(): array
    {
        $request = Http::withHeaders([
            'Authorization' => $this->token,
        ])->get(config('focusnfe.URL.production') . self::URL . "/inutilizacoes");

        if ($request->failed()) {
            Log::error('FocusNFe.NFe: Erro ao consultar inutilizações', [
                'response' => $request->json()
            ]);
        }

        return $request->json();
    }
}
