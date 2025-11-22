<?php

namespace Sysborg\FocusNFe\app\Services;

use Illuminate\Support\Facades\Http;
use Log;

/**
 * Classe responsável por manipular o envio de NFSe via arquivo XML
 */
class NFSeArquivo 
{
    /**
     * URL base da API NFSe por Arquivo
     * 
     * @var string
     */
     const URL = '/v2/lotes_rps';

    /**
     * Ambiente de produção ou sandbox
     * 
     * @var string
     */
    private string $ambiente;

    /**
     * Token de acesso à API
     * 
     * @var string
     */
    private string $token;

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
     * Envia os dados da NFSe para processamento
     *
     * @param string $referencia
     * @param \Illuminate\Http\UploadedFile $arquivo
     * @return array
     */
    public function envia(string $referencia, $arquivo): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->attach(
            'arquivo',
            file_get_contents($arquivo->getPathname()),
            $arquivo->getClientOriginalName()
        )->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref={$referencia}");
    
        if ($request->failed()) {
            Log::error('FocusNFe.NFSeArquivo: Erro ao enviar arquivo NFSe', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }
    
        return $request->json();
    }
    

    public function get(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($request->failed()) {
            Log::error('FocusNFe.LotesRPS: Erro ao buscar lote RPS', [
                'response' => $request->json(),
                'data' => [
                    'referencia' => $referencia,
                ]
            ]);
        }

        return $request->json();
    }
}
