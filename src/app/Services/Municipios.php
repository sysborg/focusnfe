<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;


class Municipios
{
    /**
     * URL base da API de Municípios
     *
     * @var string
     */
    const URL = '/v2/municipios';

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
     * Lista os municípios com filtros opcionais
     *
     * @param int $offset
     * @param string|null $codigo
     * @param string|null $descricao
     * @return Response
     */
    public function list(int $offset = 1, ?string $codigo = null, ?string $descricao = null): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&codigo=$codigo&descricao=$descricao");

        if ($response->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao listar municípios', [
                'response' => $response->json(),
                'data' => [
                    'offset' => $offset,
                    'codigo' => $codigo,
                    'descricao' => $descricao
                ]
            ]);
        }

        return $response;
    }

    /**
     * Retorna os detalhes de um município específico pelo código
     *
     * @param string $codigo
     * @return Response
     */
    public function get(string $codigo): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

        if ($response->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar município', [
                'response' => $response->json(),
                'data' => [
                    'codigo' => $codigo
                ]
            ]);
        }

        return $response;
    }


     /**
     * Retorna os itens da lista de serviço para um município específico
     *
     * @param string $codigoMunicipio
     * @param string $codigoServico
     * @return Response
     */
    public function getListaServico(string $codigoMunicipio, string $codigoServico): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigoMunicipio/itens_lista_servico/$codigoServico");

        if ($response->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar itens de serviço', [
                'response' => $response->json(),
                'data' => [
                    'codigoMunicipio' => $codigoMunicipio,
                    'codigoServico' => $codigoServico
                ]
            ]);
        }

        return $response;
    }

    /**
     * Retorna os códigos tributários municipais para um município específico
     *
     * @param string $codigoMunicipio
     * @param string $codigoTributario
     * @return Response
     */
    public function getCodigosTributarios(string $codigoMunicipio, string $codigoTributario): Response
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigoMunicipio/codigos_tributarios_municipio/$codigoTributario");

        if ($response->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar códigos tributários', [
                'response' => $response->json(),
                'data' => [
                    'codigoMunicipio' => $codigoMunicipio,
                    'codigoTributario' => $codigoTributario
                ]
            ]);
        }

        return $response;
    }
}
