<?php

namespace Sysborg\FocusNfe\app\Services;

use Sysborg\FocusNfe\app\Services\FocusNfeLogger;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\EmpresaDTO;
use Sysborg\FocusNfe\app\Events\EmpresaCreated;
use Sysborg\FocusNfe\app\Events\EmpresaUpdated;
use Sysborg\FocusNfe\app\Events\EmpresaDeleted;

/**
 * Serviço responsável por gerenciar empresas via API FocusNFe
 */
class Empresas extends EventHelper
{
    /**
     * URL base da API Empresas
     *
     * @var string
     */
    public const URL = '/v2/empresas';

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
     * Cria uma nova empresa
     *
     * @param EmpresaDTO $data
     * @return Response
     */
    public function create(EmpresaDTO $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;

        FocusNfeLogger::info('FocusNfe.Empresa: Criando nova empresa', [
          'url' => $url,
          'data' => $data->toArray(),
        ]);

        $response = FocusNfeHttp::withToken($this->token)->post($url, $data->toArray());

        $this->dispatch(EmpresaCreated::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Empresa: Erro ao criar empresa', $this->ambiente, 'post', $url, $response, [
                'data' => $data->toArray(),
            ]);
        }

        FocusNfeLogger::info('FocusNfe.Empresa: Empresa criada com sucesso', [
          'response' => $response->json(),
          'data' => $data->toArray(),
        ]);

        return $response;
    }

    /**
     * Lista todas as empresas
     *
     * @param int $offset
     * @param string|null $cnpj
     * @param string|null $cpf
     * @return Response
     */
    public function list(int $offset = 1, ?string $cnpj = null, ?string $cpf = null): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&cnpj=$cnpj&cpf=$cpf";

        FocusNfeLogger::info('FocusNfe.Empresa: Listando empresas', [
          'url' => $url,
          'data' => [
            'offset' => $offset,
            'cnpj' => $cnpj,
            'cpf' => $cpf
          ]
        ]);

        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Empresa: Erro ao listar empresas', $this->ambiente, 'get', $url, $response, [
                'data' => [
                    'offset' => $offset,
                    'cnpj' => $cnpj,
                    'cpf' => $cpf,
                ],
            ]);
        }

        return $response;
    }

    /**
     * Pega uma empresa por id
     *
     * @param int $id
     * @return Response
     */
    public function get(int $id): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->get($url);

        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Empresa: Erro ao pegar empresa', $this->ambiente, 'get', $url, $response, [
                'data' => [
                    'id' => $id,
                ],
            ]);
        }

        return $response;
    }

    /**
     * Atualiza uma empresa
     *
     * @param int $id
     * @param EmpresaDTO $data
     * @return Response
     */
    public function update(int $id, EmpresaDTO $data): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->put($url, $data->toArray());

        $this->dispatch(EmpresaUpdated::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Empresa: Erro ao atualizar empresa', $this->ambiente, 'put', $url, $response, [
                'data' => [
                    'id' => $id,
                    'data' => $data->toArray(),
                ],
            ]);
        }

        return $response;
    }

    /**
     * Deleta uma empresa
     *
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id";
        $response = FocusNfeHttp::withToken($this->token)->delete($url);

        $this->dispatch(EmpresaDeleted::class, $response);
        if ($response->failed()) {
            FocusNfeLogger::apiError('FocusNfe.Empresa: Erro ao deletar empresa', $this->ambiente, 'delete', $url, $response, [
                'data' => [
                    'id' => $id,
                ],
            ]);
        }

        return $response;
    }
}
