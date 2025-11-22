<?php

namespace Sysborg\FocusNFe\app\Services;
use Log;
use Illuminate\Support\Facades\Http;

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
      Log::error('FocusNFe.NCM: Erro ao listar NCMs', [
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
   * Retorna o NCM pelo código exato
   *
   * @param string $codigo
   * @return array
   */
  public function get(string $codigo): array
  {

      $request = Http::withHeaders([
          'Authorization' => 'Basic ' . base64_encode($this->token),
      ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

      if ($request->failed()) {
          Log::error('FocusNFe.NCM: Erro ao buscar NCM', [
            'response' => $request->json(),
            'data' => [
              'ncm' => $codigo
            ]
          ]);
        }


      return $request->json();
    }
}
