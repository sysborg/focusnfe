<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CFOP {
  /**
   * URL base da API CFOP
   * 
   * @var string
   */
  const URL = '/v2/cfops';

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
   * Lista todos os CFOPs
   *
   * @param int $offset
   * @param string|null $codigo
   * @param string|null $descricao
   * @return Response
   */
  public function list(int $offset = 1, ?string $codigo = NULL, ?string $descricao = NULL): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&codigo=$codigo&descricao=$descricao");

    if ($response->failed()) {
      Log::error('FocusNfe.CFOP: Erro ao listar CFOPs', [
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
   * Retorna o CFOP pelo código
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
      Log::error('FocusNfe.CFOP: Erro ao buscar CFOP', [
        'response' => $response->json(),
        'data' => [
          'codigo' => $codigo
        ]
      ]);
    }

    return $response;
  }
}
