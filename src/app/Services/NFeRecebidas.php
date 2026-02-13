<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFeRecebidasDTO;

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
     * @return Response
     */
    public function listByCnpj(string $cnpj): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        if ($response->failed()) {
            Log::error('FocusNFe.NFeRecebidas: Erro ao listar NFe Recebidas', [
                'response' => $response->json(),
                'cnpj' => $cnpj
            ]);
        }

        return $response;
    }


    /**
     * Realiza um manifesto na nota informada
     *
     * @param string $chave
     * @param NFeRecebidasDTO $data
     * @return Response
     */
    public function manifestar(string $chave, NFeRecebidasDTO $data): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/manifesto", $data);

        if ($response->failed()) {
            Log::error('FocusNFe.NFeRecebidas: Erro ao manifestar NFe', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }

    /**
     * Consulta o último manifesto válido na nota fiscal informada
     *
     * @param string $chave
     * @return Response
     */
    public function consultarManifesto(string $chave): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/manifesto");

        if ($response->failed()) {
            Log::error('FocusNFe.NFeRecebidas: Erro ao consultar manifesto NFe', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }
}
