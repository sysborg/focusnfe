<?php

namespace Sysborg\FocusNFe\app\Services;

use Illuminate\Support\Facades\Http;
use Log;

class NFSeRecebidas
{
    /**
     * URL base da API NFSe Recebidas
     * 
     * @var string
     */
    const URL = '/v2/nfses_recebidas';

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
     * Lista todas as NFSe Recebidas de um CNPJ específico.
     * 
     * @param string $cnpj
     * @return array
     */
    public function listByCnpj(string $cnpj): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.production') . self::URL . "?cnpj=$cnpj");

        if ($request->failed()) {
            Log::error('FocusNFe.NFSeRecebidas: Erro ao listar NFSe Recebidas', [
                'response' => $request->json(),
                'cnpj' => $cnpj
            ]);
        }

        return $request->json();
    }

    /**
     * Retorna uma NFSe Recebida pela CHAVE.
     * 
     * @param string $chave
     * @return array
     */
    public function getByChave(string $chave): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.production') . self::URL . "/$chave");

        if ($request->failed()) {
            Log::error('FocusNFe.NFSeRecebidas: Erro ao buscar NFSe Recebida', [
                'response' => $request->json(),
                'chave' => $chave
            ]);
        }

        return $request->json();
    }
}
