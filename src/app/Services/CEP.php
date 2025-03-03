<?php

namespace Sysborg\FocusNFe\App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


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
   * Pega um cep por sua numeração
   * 
   * @param string $cep
   * @return array
   */
  public function get(string $cep): string
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$cep");

    if ($request->failed()) {
      Log::error('FocusNFe.Cep: Erro ao consultar CEP', [
        'response' => $request->json(),
        'data' => [
          'cep' => $cep
        ]
      ]);
    }

    return $request->json();
  }

}
