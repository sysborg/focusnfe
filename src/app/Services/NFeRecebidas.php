<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\DTO\NFeRecebidasDTO;
use Illuminate\Support\Facades\Http;
use Log;

class NFeRecebidas
{
    /**
     * URL base da API NFe Recebidas
     * 
     * @var string
     */
    const URL = '/v2/nfes_recebidas';

    /**
     * Ambiente de produção ou sandbox
     * 
     * @var string
     */
    private string $ambiente;

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
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Lista todas as NFe Recebidas de um CNPJ específico.
     * 
     * @param string $cnpj
     * @return array
     */
    public function listByCnpj(string $cnpj): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        if ($request->failed()) {
            Log::error('FocusNFe.NFeRecebidas: Erro ao listar NFe Recebidas', [
                'response' => $request->json(),
                'cnpj' => $cnpj
            ]);
        }

        return $request->json();
    }

   
    /**
     * Realiza um manifesto na nota informada
     * 
     * @param string $chave
     * @param array $data
     * @return array
     */
    public function manifestar(string $chave, NFeRecebidasDTO $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/manifesto", $data);

        return $request->json();
    }

    /**
     * Consulta o último manifesto válido na nota fiscal informada
     * 
     * @param string $chave
     * @return array
     */
    public function consultarManifesto(string $chave): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/manifesto");

        return $request->json();
    }
}