<?php

namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CEP {
  /**
   * URL base da API CEP
   * 
   * @var string
   */
  const URL = '/v2/ceps';

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
   * Pega um cep por sua numeração
   *
   * @param string $cep
   * @return Response
   */
  public function get(string $cep): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$cep");

    if ($response->failed()) {
      Log::error('FocusNfe.Cep: Erro ao consultar CEP', [
        'response' => $response->json(),
        'data' => [
          'cep' => $cep
        ]
      ]);
    }

    return $response;
  }
}
