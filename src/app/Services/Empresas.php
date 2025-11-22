<?php

namespace Sysborg\FocusNFe\app\Services;
use Illuminate\Support\Facades\Log;
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
   * @return array
   */
  public function create(EmpresaDTO $data): array
  {
    $url = config('focusnfe.URL.' . $this->ambiente) . self::URL;

    Log::info('FocusNFe.Empresa: Criando nova empresa', [
      'url' => $url,
      'data' => $data->toArray(),
    ]);

    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post($url, $data->toArray());

    $this->dispatch(EmpresaCreated::class, $request);
    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao criar empresa', [
        'response' => $request->json(),
        'data' => $data->toArray()
      ]);
    }

    Log::info('FocusNFe.Empresa: Empresa criada com sucesso', [
      'response' => $request->json(),
      'data' => $data->toArray(),
    ]);

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
    $url = config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&cnpj=$cnpj&cpf=$cpf";

    Log::info('FocusNFe.Empresa: Listando empresas', [
      'url' => $url,
      'data' => [
        'offset' => $offset,
        'cnpj' => $cnpj,
        'cpf' => $cpf
      ]
    ]);

    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get($url);

    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao listar empresas', [
        'response' => $request->json(),
        'status' => $request->status(),
        'data' => [
          'offset' => $offset,
          'cnpj' => $cnpj,
          'cpf' => $cpf
        ]
      ]);

      return [
        'total' => $request->header('X-Total-Count', 0),
        'data' => [],
        'status' => $request->status(),
      ];
    }

    return [
      'total' => $request->header('X-Total-Count', 0),
      'data' => $request->json(),
      'status' => $request->status(),
    ];
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
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id");

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
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->put(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id", $data->toArray());

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
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$id");

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
