<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

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
     * Lista todas as NFSe Recebidas de um CNPJ específico.
     *
     * @param string $cnpj
     * @return Response
     */
    public function listByCnpj(string $cnpj): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        if ($response->failed()) {
            Log::error('FocusNFe.NFSeRecebidas: Erro ao listar NFSe Recebidas', [
                'response' => $response->json(),
                'cnpj' => $cnpj
            ]);
        }

        return $response;
    }

    /**
     * Retorna uma NFSe Recebida pela CHAVE.
     *
     * @param string $chave
     * @return Response
     */
    public function getByChave(string $chave): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave");

        if ($response->failed()) {
            Log::error('FocusNFe.NFSeRecebidas: Erro ao buscar NFSe Recebida', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }
}
