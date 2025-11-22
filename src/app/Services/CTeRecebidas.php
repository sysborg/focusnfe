<?php

namespace Sysborg\FocusNFe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\app\DTO\CTERecebidasDTO;

/**
 * Classe responsável por manipular as CTE Recebidas
 */
class CTERecebidas {
    /**
     * URL base da API CTE Recebidas
     * 
     * @var string
     */
    const URL = '/v2/ctes_recebidas';

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
     * Consulta CTEs Recebidas por CNPJ
     * 
     * @param string $cnpj
     * @return array
     */
    public function consulta(string $cnpj): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        return $request->json();
    }

    /**
     * Consulta um CTE individual
     * 
     * @param string $chave
     * @return array
     */
    public function consultaCTE(string $chave): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave");

        return $request->json();
    }

    /**
     * Informa desacordo de um CTE recebido
     * 
     * @param string $chave
     * @return array
     */
    public function informarDesacordo(string $chave, CTERecebidasDTO $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/desacordo", [
            'observacoes' => $data->observacoes
        ]);

        return $request->json();
    }

    /**
     * Consulta o último desacordo válido para um CTE informado
     * 
     * @param string $chave
     * @return array
     */
    public function consultaDesacordo(string $chave): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/desacordo");

        return $request->json();
    }
}
