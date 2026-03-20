<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;

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
    public const URL = '/v2/lotes_rps';

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
     * @return Response
     */
    public function envia(string $referencia, $arquivo): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->pending()->attach(
            'arquivo',
            file_get_contents($arquivo->getPathname()),
            $arquivo->getClientOriginalName()
        )->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?ref={$referencia}");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.NFSeArquivo: Erro ao enviar arquivo NFSe', [
                'response' => $response->json(),
                'referencia' => $referencia
            ]);
        }

        return $response;
    }


    /**
     * Consulta um lote de RPS enviado por arquivo
     *
     * @param string $referencia
     * @return Response
     */
    public function get(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNFe.LotesRPS: Erro ao buscar lote RPS', [
                'response' => $response->json(),
                'data' => [
                    'referencia' => $referencia,
                ]
            ]);
        }

        return $response;
    }
}
