<?php
namespace Sysborg\FocusNFe\App\Services;

use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\App\DTO\NFSeDTO;
use Log;

/**
 * Classe responsável por manipular as NFSe
 * https://focusnfe.com.br/doc/?php#nfse
 */

class NFSe {
  /**
   * URL base da API NFSe
   * 
   * @var string
   */
  const URL = '/v2/nfse';

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
   * Envia uma NFSe
   * 
   * @param NFSeDTO $data
   * @return array
   */
  public function envia(NFSeDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL, $data->toArray());

    if ($request->failed()) {
      Log::error('FocusNFe.NFSe: Erro ao enviar NFSe', [
        'response' => $request->json(),
        'data' => $data->toArray()
      ]);
    }

    return $request->json();
  }

  /**
   * Consulta uma NFSe
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
      Log::error('FocusNFe.NFSe: Erro ao consultar NFSe', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }

  /**
   * Cancelamento de uma NFSe
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
      Log::error('FocusNFe.NFSe: Erro ao cancelar NFSe', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }

  /**
   * Reenvia email da NFSe
   * 
   * @param string $referencia
   * @param string $email
   * @return array
   */
  public function reenviaEmail(string $referencia, string $email): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL . "/$referencia/$email");

    if ($request->failed()) {
      Log::error('FocusNFe.NFSe: Erro ao reenviar email da NFSe', [
        'response' => $request->json(),
        'referencia' => $referencia,
        'email' => $email
      ]);
    }

    return $request->json();
  }
}
