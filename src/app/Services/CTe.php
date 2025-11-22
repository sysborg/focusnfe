<?php

namespace Sysborg\FocusNFe\app\Services;

use Illuminate\Support\Facades\Http;
use Log;

class CTe{
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
     * @return array
     */
    public function envia(array $data, string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref=$referencia", $data);
    
        if ($request->failed()) {
            Log::error('FocusNFe.CTe: Erro ao enviar CTe', [
                'response' => $request->json(),
                'data' => $data,
                'referencia' => $referencia
            ]);
        }
    
        return $request->json();
    }

    /**
     * Consulta uma CTe
     * 
     * @param string $referencia
     * @return array
     */
    public function consulta(string $referencia): array
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";

        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->get($url);

        if ($request->failed()) {
            Log::error('FocusNFe.CTe: Erro ao consultar CTe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Cancela uma CTe
     * 
     * @param string $referencia
     * @return array
     */
    public function cancela(string $referencia): array
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";

        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->delete($url);

        if ($request->failed()) {
            Log::error('FocusNFe.CTe: Erro ao cancelar CTe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

     /**
     * Cria uma Carta de Correção para uma CTe
     *
     * @param string $referencia
     * @param array $data
     * @return array
     */
    public function cartaCorrecao(string $referencia, array $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/carta_correcao", $data);

        if ($request->failed()) {
            Log::error('FocusNFe.CTe: Erro ao enviar Carta de Correção', [
                'response' => $request->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $request->json();
    }
}
