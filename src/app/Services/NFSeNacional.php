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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;
        $response = FocusNfeHttp::withToken($this->token)->post($url, $data->toArray());

        $this->dispatch(NFSeNacionalAutorizada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSeN: Erro ao enviar NFSe Nacional', $this->ambiente, 'post', $url, $response, [
                'data' => $data->toArray(),
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSeN: Erro ao consultar NFSe Nacional', $this->ambiente, 'get', $url, $response, [
                'referencia' => $referencia,
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
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        $this->dispatch(NFSeNacionalCancelada::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.NFSeN: Erro ao cancelar NFSe Nacional', $this->ambiente, 'delete', $url, $response, [
                'referencia' => $referencia,
            ]);
        }

        return $response;
    }
}
