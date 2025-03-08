<?php

namespace Sysborg\FocusNFe\app\Services;
use Log;
use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\app\DTO\EmpresaDTO;
use Sysborg\FocusNFe\app\Events\EmpresaCreated;
use Sysborg\FocusNFe\app\Events\EmpresaUpdated;
use Sysborg\FocusNFe\app\Events\EmpresaDeleted;

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
   * Construtor da classe
   * 
   * @param string $token
   * @return void
   */
  public function __construct(string $token)
  {
    $this->token = $token;
  }

  /**
   * Cria uma nova empresa
   * 
   * @param EmpresaDTO $data
   * @return array
   */
  public function create(EmpresaDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL, $data->toArray());

    $this->dispatch(EmpresaCreated::class, $request);
    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao criar empresa', [
        'response' => $request->json(),
        'data' => $data->toArray()
      ]);
    }

    return $request->json();
  }

  /**
   * Lista todas as empresas
   * 
   * @param int $offset
   * @param string|null $cnpj
   * @param string|null $cpf
   * @return array
   */
  public function list(int $offset = 1, ?string $cnpj = NULL, ?string $cpf = NULL): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "?offset=$offset&cnpj=$cnpj&cpf=$cpf");

    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao listar empresas', [
        'response' => $request->json(),
        'data' => [
          'offset' => $offset,
          'cnpj' => $cnpj,
          'cpf' => $cpf
        ]
      ]);
    }

    return $request->json();
  }

  /**
   * Pega uma empresa por id
   * 
   * @param int $id
   * @return array
   */
  public function get(int $id): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$id");

    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao pegar empresa', [
        'response' => $request->json(),
        'data' => [
          'id' => $id
        ]
      ]);
    }

    return $request->json();
  }

  /**
   * Atualiza uma empresa
   * 
   * @param int $id
   * @param EmpresaDTO $data
   * @return array
   */
  public function update(int $id, EmpresaDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->put(config('focusnfe.URL.production') . self::URL . "/$id", $data->toArray());

    $this->dispatch(EmpresaUpdated::class, $request);
    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao atualizar empresa', [
        'response' => $request->json(),
        'data' => [
          'id' => $id,
          'data' => $data->toArray()
        ]
      ]);
    }

    return $request->json();
  }

  /**
   * Deleta uma empresa
   * 
   * @param int $id
   * @return array
   */
  public function delete(int $id): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->delete(config('focusnfe.URL.production') . self::URL . "/$id");

    $this->dispatch(EmpresaDeleted::class, $request);
    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao deletar empresa', [
        'response' => $request->json(),
        'data' => [
          'id' => $id
        ]
      ]);
    }

    return $request->json();
  }
}
