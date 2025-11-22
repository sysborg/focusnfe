<?php

namespace Sysborg\FocusNFe\app\Services;
use Illuminate\Support\Facades\Http;
use Log;

class Backups {
  /**
   * URL base da API Backups
   * 
   * @var string
   */
  const URL = '/v2/backups/CNPJ.json';

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
   * Pega os backups de um determinado CNPJ
   * 
   * @param string $cnpj
   * @return array
   */
  public function get(string $cnpj): string
  {
    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . $this->token,
    ])->get(config('focusnfe.URL.' . $this->ambiente) . sprintf(self::URL, $cnpj));

    if ($request->failed()) {
      Log::error('FocusNFe.Backups: Erro ao consultar backup do CNPJ', [
        'response' => $request->json(),
        'cnpj' => $cnpj
      ]);
    }

    return $request->json();
  }
}
