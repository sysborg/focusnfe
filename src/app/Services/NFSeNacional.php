<?php
namespace Sysborg\FocusNFe\app\Services;

use Log;
use Sysborg\FocusNFe\app\DTO\NFSenDTO;
use Illuminate\Support\Facades\Http;

/**
 * Classe responsável por manipular as NFSe Nacional
 * 
 */

class NFSeNacional {
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
   * Envia uma NFSe Nacional
   * 
   * @param NFSeNDTO $data
   * @return array
   */
  public function envia(NFSenDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data->toArray());

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
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

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
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

    if ($request->failed()) {
      Log::error('FocusNFe.NFSeN: Erro ao cancelar NFSe Nacional', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }
}
