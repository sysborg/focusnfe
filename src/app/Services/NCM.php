<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class NCM {
  /**
   * URL base da API NCM
   * 
   * @var string
   */
  const URL = '/v2/ncms';

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
   * Lista todos os NCMs
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
      Log::error('FocusNfe.NCM: Erro ao listar NCMs', [
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
   * Retorna o NCM pelo código exato
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
          Log::error('FocusNfe.NCM: Erro ao buscar NCM', [
            'response' => $response->json(),
            'data' => [
              'ncm' => $codigo
            ]
          ]);
        }

      return $response;
    }
}
