<?php

namespace Sysborg\FocusNFe\app\Services;
use Illuminate\Support\Facades\Http;
use Log;

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
   * @return array
   */
  public function get(string $cep): string
  {
    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$cep");

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
