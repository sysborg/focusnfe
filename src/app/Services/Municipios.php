<?php

namespace Sysborg\FocusNFe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;


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
     * @return array
     */
    public function list(int $offset = 1, ?string $codigo = null, ?string $descricao = null): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&codigo=$codigo&descricao=$descricao");

        if ($request->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao listar municípios', [
                'response' => $request->json(),
                'data' => [
                    'offset' => $offset,
                    'codigo' => $codigo,
                    'descricao' => $descricao
                ]
            ]);

            return ['erro' => 'Falha ao buscar municípios.'];
        }

        return $request->json();
    }

    /**
     * Retorna os detalhes de um município específico pelo código
     *
     * @param string $codigo
     * @return array
     */
    public function get(string $codigo): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

        if ($request->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar município', [
                'response' => $request->json(),
                'data' => [
                    'codigo' => $codigo
                ]
            ]);
        }

        return $request->json();
    }


     /**
     * Retorna os itens da lista de serviço para um município específico
     *
     * @param string $codigoMunicipio
     * @param string $codigoServico
     * @return array
     */
    public function getListaServico(string $codigoMunicipio, string $codigoServico): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigoMunicipio/itens_lista_servico/$codigoServico");

        if ($request->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar itens de serviço', [
                'response' => $request->json(),
                'data' => [
                    'codigoMunicipio' => $codigoMunicipio,
                    'codigoServico' => $codigoServico
                ]
            ]);
        }

        return $request->json();
    }

    /**
     * Retorna os códigos tributários municipais para um município específico
     *
     * @param string $codigoMunicipio
     * @param string $codigoTributario
     * @return array
     */
    public function getCodigosTributarios(string $codigoMunicipio, string $codigoTributario): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigoMunicipio/codigos_tributarios_municipio/$codigoTributario");

        if ($request->failed()) {
            Log::error('FocusNFe.Municipios: Erro ao buscar códigos tributários', [
                'response' => $request->json(),
                'data' => [
                    'codigoMunicipio' => $codigoMunicipio,
                    'codigoTributario' => $codigoTributario
                ]
            ]);
        }

        return $request->json();
    }
}
