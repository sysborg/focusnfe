<?php

namespace Sysborg\FocusNFe\App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class Cnpjs {
  /**
   * URL base da API CNPJS
   * 
   * @var string
   */
  const URL = '/v2/cnpjs';

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
   * Pega um cnpj por sua numeração
   * 
   * @param string $cnpj
   * @return array
   */
  public function get(string $cnpj): string
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$cnpj");

    if ($request->failed()) {
      Log::error('FocusNFe.Empresa: Erro ao consultar CNPJ', [
        'response' => $request->json(),
        'data' => [
          'cnpj' => $cnpj
        ]
      ]);
    }

    return $request->json();
  }

}
