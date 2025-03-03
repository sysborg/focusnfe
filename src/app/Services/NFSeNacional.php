<?php
namespace Sysborg\FocusNFe\App\Services;

use Log;
use NFSeNDTO;
use Illuminate\Support\Facades\Http;

/**
 * Classe responsável por manipular as NFSe Nacional
 * https://focusnfe.com.br/doc/?php#nfse-nacional_envio
 */

class NFSen {
  /**
   * URL base da API NFSe Nacional
   * 
   * @var string
   */
  const URL = '/v2/nfsen';

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
   * Envia uma NFSe Nacional
   * 
   * @param NFSeNDTO $data
   * @return array
   */
  public function envia(NFSeNDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL, $data->toArray());

    if ($request->failed()) {
      Log::error('FocusNFe.NFSeN: Erro ao enviar NFSe Nacional', [
        'response' => $request->json(),
        'data' => $data->toArray()
      ]);
    }

    return $request->json();
  }

  /**
   * Consulta uma NFSe Nacional
   * 
   * @param string $referencia
   * @return array
   */
  public function consulta(string $referencia): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$referencia");

    if ($request->failed()) {
      Log::error('FocusNFe.NFSeN: Erro ao consultar NFSe Nacional', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }

  /**
   * Cancela uma NFSe Nacional
   * 
   * @param string $referencia
   * @return array
   */
  public function cancela(string $referencia): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->delete(config('focusnfe.URL.production') . self::URL . "/$referencia");

    if ($request->failed()) {
      Log::error('FocusNFe.NFSeN: Erro ao cancelar NFSe Nacional', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }
}
