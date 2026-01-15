<?php
namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\Events\NFSeEnviada;
use Sysborg\FocusNfe\app\Events\NFSeCancelada;
use Illuminate\Http\Client\Response;

/**
 * Classe responsável por manipular as NFSe
 * https://focusnfe.com.br/doc/?php#nfse
 */

class NFSe extends EventHelper {
  /**
   * URL base da API NFSe
   * 
   * @var string
   */
  const URL = '/v2/nfse';

  /**
   * Ambiente de produção ou sandbox
   * 
   * @var string
   */
  private string $ambiente;

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
  public function __construct(string $token, string $ambiente)
  {
    $this->token = $token;
    $this->ambiente = $ambiente;
  }

  /**
   * Envia uma NFSe
   * 
   * @param NFSeDTO $data
   * @param string $ref - Número interno da NFSe para referência
   * @return array
   */
  public function envia(NFSeDTO $data, string $ref): array
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data->toArray());

    if (!($response instanceof Response)) {
      Log::error('FocusNFe.NFSe: Resposta inválida ao enviar NFSe', [
        'data' => $data->toArray(),
        'response' => $response
      ]);

      throw new \Exception('Resposta inválida ao enviar NFSe: ' . print_r($response, true));
    }

    Log::debug('FocusNFe.NFSe: Enviando NFSe', [
      'url' => config('focusnfe.URL.' . $this->ambiente) . self::URL,
      'data' => $data->toArray(),
      'response' => $response
    ]);

    $this->dispatch($response, NFSeEnviada::class);
    if ($response->failed()) {
      Log::error('FocusNFe.NFSe: Erro ao enviar NFSe', [
        'response' => $response->json(),
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
  public function get(string $referencia): array
  {
    $request = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

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
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

    $this->dispatch($request, NFSeCancelada::class);
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
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/$email");

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
