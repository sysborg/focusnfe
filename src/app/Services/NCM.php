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
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "?offset=$offset&codigo=$codigo&descricao=$descricao");

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
        'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$codigo");

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
