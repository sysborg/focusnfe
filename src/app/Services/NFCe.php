<?php
namespace Sysborg\FocusNFe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Sysborg\FocusNFe\App\DTO\NFCeDTO;

/**
 * Classe responsável por manipular as NFCe
 * 
 */
class NFCe {
  /**
   * URL base da API NFCe
   * 
   * @var string
   */
  const URL = '/v2/nfce';

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
   * Envia uma NFCe
   * 
   * @param NFCeDTO $data
   * @return array
   */
  public function envia(NFCeDTO $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL, $data->toArray());

    if ($request->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao enviar NFCe', [
        'response' => $request->json(),
        'data' => $data->toArray()
      ]);
    }

    return $request->json();
  }

  /**
   * Consulta uma NFCe
   * 
   * @param string $referencia
   * @return array
   */
  public function get(string $referencia): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$referencia");

    if ($request->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao consultar NFCe', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }

  /**
   * Cancela uma NFCe
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
      Log::error('FocusNFe.NFCe: Erro ao cancelar NFCe', [
        'response' => $request->json(),
        'referencia' => $referencia
      ]);
    }

    return $request->json();
  }

  /**
   * Consulta numerações inutilizadas
   * 
   * @return array
   */
  public function inutilizacoes(): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/inutilizacoes");

    return $request->json();
  }

  /**
   * Registra um evento de Conciliação Financeira - ECONF
   * 
   * @param string $referencia
   * @param array $data
   * @return array
   */
  public function registraEconf(string $referencia, array $data): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->post(config('focusnfe.URL.production') . self::URL . "/$referencia/econf", $data);

    return $request->json();
  }


  /**
   * Consulta um evento de Conciliação Financeira - ECONF
   * 
   * @param string $referencia
   * @param string $protocolo
   * @return array
   */
  public function consultaEconf(string $referencia, string $protocolo): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$referencia/econf/$protocolo");

    return $request->json();
  }


  /**
   * Cancela um evento de Conciliação Financeira - ECONF
   * 
   * @param string $referencia
   * @param string $protocolo
   * @return array
   */
  public function cancelaEconf(string $referencia, string $protocolo): array
  {
    $request = Http::withHeaders([
      'Authorization' => $this->token,
    ])->delete(config('focusnfe.URL.production') . self::URL . "/$referencia/econf/$protocolo");

    return $request->json();
  }
}

