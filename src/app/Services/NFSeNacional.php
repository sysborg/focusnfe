<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;

/**
 * Serviço responsável por manipular as NFSe Nacional via API FocusNFe
 */
class NFSeNacional
{
    /**
     * URL base da API NFSe Nacional
     *
     * @var string
     */
    public const URL = '/v2/nfsen';

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
     * Envia uma NFSe Nacional
     *
     * @param NFSeNDTO $data
     * @return Response
     */
    public function envia(NFSeNDTO $data): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data->toArray());

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFSeN: Erro ao enviar NFSe Nacional', [
              'response' => $response->json(),
              'data' => $data->toArray()
            ]);
        }

        return $response;
    }

    /**
     * Consulta uma NFSe Nacional
     *
     * @param string $referencia
     * @return Response
     */
    public function consulta(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFSeN: Erro ao consultar NFSe Nacional', [
              'response' => $response->json(),
              'referencia' => $referencia
            ]);
        }

        return $response;
    }

    /**
     * Cancela uma NFSe Nacional
     *
     * @param string $referencia
     * @return Response
     */
    public function cancela(string $referencia): Response
    {
        $response = FocusNfeHttp::withToken($this->token)->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFSeN: Erro ao cancelar NFSe Nacional', [
              'response' => $response->json(),
              'referencia' => $referencia
            ]);
        }

        return $response;
    }
}
