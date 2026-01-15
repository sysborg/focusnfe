<?php

namespace Sysborg\FocusNfe\app\Services;
use Log;
use Illuminate\Support\Facades\Http;

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
   * https://focusnfe.com.br/doc/?php#consulta-de-cfop
   * 
   * @param int $offset
   * @param string|null $cnpj
   * @param string|null $cpf
   * @return array
   */
  public function list(int $offset = 1, ?string $codigo = NULL, ?string $descricao = NULL): array
  {
    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset&codigo=$codigo&descricao=$descricao");

    if ($request->failed()) {
      Log::error('FocusNFe.CFOP: Erro ao listar CFOPs', [
        'response' => $request->json(),
        'data' => [
          'offset' => $offset,
          'codigo' => $codigo,
          'descricao' => $descricao
        ]
      ]);
    }

    return $request->json();
  }

  /**
   * Retorna o CFOP pelo código
   * 
   * @param string $codigo
   * @return array
   */
  public function get(string $codigo): array
  {
    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.production') . self::URL . "/$codigo");

    if ($request->failed()) {
      Log::error('FocusNFe.CFOP: Erro ao buscar CFOP', [
        'response' => $request->json(),
        'data' => [
          'codigo' => $codigo
        ]
      ]);
    }

    return $request->json();
  }
}
