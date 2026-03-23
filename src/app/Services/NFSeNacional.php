<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;
use Sysborg\FocusNfe\app\Events\NFSeNacionalAutorizada;
use Sysborg\FocusNfe\app\Events\NFSeNacionalCancelada;

/**
 * Serviço responsável por manipular as NFSe Nacional via API FocusNFe
 */
class NFSeNacional extends EventHelper
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

        $this->dispatch(NFSeNacionalAutorizada::class, $response);
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

        $this->dispatch(NFSeNacionalCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::error('FocusNfe.NFSeN: Erro ao cancelar NFSe Nacional', [
              'response' => $response->json(),
              'referencia' => $referencia
            ]);
        }

        return $response;
    }
}
