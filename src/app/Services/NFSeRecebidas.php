<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

/**
 * Serviço responsável por manipular as NFSe Recebidas via API FocusNFe
 */
class NFSeRecebidas
{
    /**
     * URL base da API NFSe Recebidas
     *
     * @var string
     */
    public const URL = '/v2/nfses_recebidas';

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
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.NFSeRecebidas: Erro ao listar NFSe Recebidas', [
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
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.NFSeRecebidas: Erro ao buscar NFSe Recebida', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }
}
