<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\EmpresaDTO;
use Sysborg\FocusNfe\app\Events\EmpresaCreated;
use Sysborg\FocusNfe\app\Events\EmpresaUpdated;
use Sysborg\FocusNfe\app\Events\EmpresaDeleted;

class Empresas extends EventHelper
{
  /**
   * URL base da API Empresas
   * 
   * @var string
   */
  const URL = '/v2/empresas';

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

    Log::info('FocusNfe.Empresa: Criando nova empresa', [
      'url' => $url,
      'data' => $data->toArray(),
    ]);

    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post($url, $data->toArray());

    $this->dispatch(EmpresaCreated::class, $response);
    if ($response->failed()) {
      Log::error('FocusNfe.Empresa: Erro ao criar empresa', [
        'response' => $response->json(),
        'data' => $data->toArray()
      ]);
    }

    Log::info('FocusNfe.Empresa: Empresa criada com sucesso', [
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
  public function list(int $offset = 1, ?string $cnpj = NULL, ?string $cpf = NULL): Response
  {
    $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&cnpj=$cnpj&cpf=$cpf";

    Log::info('FocusNfe.Empresa: Listando empresas', [
      'url' => $url,
      'data' => [
        'offset' => $offset,
        'cnpj' => $cnpj,
        'cpf' => $cpf
      ]
    ]);

    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get($url);

    if ($response->failed()) {
      Log::error('FocusNfe.Empresa: Erro ao listar empresas', [
        'response' => $response->json(),
        'status' => $response->status(),
        'data' => [
          'offset' => $offset,
          'cnpj' => $cnpj,
          'cpf' => $cpf
        ]
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
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id");

    if ($response->failed()) {
      Log::error('FocusNfe.Empresa: Erro ao pegar empresa', [
        'response' => $response->json(),
        'data' => [
          'id' => $id
        ]
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
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->put(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id", $data->toArray());

    $this->dispatch(EmpresaUpdated::class, $response);
    if ($response->failed()) {
      Log::error('FocusNfe.Empresa: Erro ao atualizar empresa', [
        'response' => $response->json(),
        'data' => [
          'id' => $id,
          'data' => $data->toArray()
        ]
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
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id");

    $this->dispatch(EmpresaDeleted::class, $response);
    if ($response->failed()) {
      Log::error('FocusNfe.Empresa: Erro ao deletar empresa', [
        'response' => $response->json(),
        'data' => [
          'id' => $id
        ]
      ]);
    }

    return $response;
  }
}
