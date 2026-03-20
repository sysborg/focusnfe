<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\CTERecebidasDTO;

/**
 * Classe responsável por manipular as CTE Recebidas
 */
class CTERecebidas
{
    /**
     * URL base da API CTE Recebidas
     *
     * @var string
     */
    public const URL = '/v2/ctes_recebidas';

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
     * @return Response
     */
    public function consulta(string $cnpj): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?cnpj=$cnpj");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CTERecebidas: Erro ao consultar CTEs Recebidas', [
                'response' => $response->json(),
                'cnpj' => $cnpj
            ]);
        }

        return $response;
    }

    /**
     * Consulta um CTE individual
     *
     * @param string $chave
     * @return Response
     */
    public function consultaCTE(string $chave): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CTERecebidas: Erro ao consultar CTE', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }

    /**
     * Informa desacordo de um CTE recebido
     *
     * @param string $chave
     * @param CTERecebidasDTO $data
     * @return Response
     */
    public function informarDesacordo(string $chave, CTERecebidasDTO $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/desacordo", [
            'observacoes' => $data->observacoes
        ]);

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CTERecebidas: Erro ao informar desacordo CTE', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }

    /**
     * Consulta o último desacordo válido para um CTE informado
     *
     * @param string $chave
     * @return Response
     */
    public function consultaDesacordo(string $chave): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$chave/desacordo");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.CTERecebidas: Erro ao consultar desacordo CTE', [
                'response' => $response->json(),
                'chave' => $chave
            ]);
        }

        return $response;
    }
}
