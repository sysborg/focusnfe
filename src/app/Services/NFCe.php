<?php
namespace Sysborg\FocusNfe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;

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
   * Envia uma NFCe
   *
   * @param NFCeDTO $data
   * @return Response
   */
  public function envia(NFCeDTO $data): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data->toArray());

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao enviar NFCe', [
        'response' => $response->json(),
        'data' => $data->toArray()
      ]);
    }

    return $response;
  }

  /**
   * Consulta uma NFCe
   *
   * @param string $referencia
   * @return Response
   */
  public function get(string $referencia): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao consultar NFCe', [
        'response' => $response->json(),
        'referencia' => $referencia
      ]);
    }

    return $response;
  }

  /**
   * Cancela uma NFCe
   *
   * @param string $referencia
   * @return Response
   */
  public function cancela(string $referencia): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao cancelar NFCe', [
        'response' => $response->json(),
        'referencia' => $referencia
      ]);
    }

    return $response;
  }

  /**
   * Consulta numerações inutilizadas
   *
   * @return Response
   */
  public function inutilizacoes(): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/inutilizacoes");

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao consultar inutilizações', [
        'response' => $response->json()
      ]);
    }

    return $response;
  }

  /**
   * Registra um evento de Conciliação Financeira - ECONF
   *
   * @param string $referencia
   * @param array $data
   * @return Response
   */
  public function registraEconf(string $referencia, array $data): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf", $data);

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao registrar ECONF', [
        'response' => $response->json(),
        'referencia' => $referencia
      ]);
    }

    return $response;
  }


  /**
   * Consulta um evento de Conciliação Financeira - ECONF
   *
   * @param string $referencia
   * @param string $protocolo
   * @return Response
   */
  public function consultaEconf(string $referencia, string $protocolo): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo");

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao consultar ECONF', [
        'response' => $response->json(),
        'referencia' => $referencia,
        'protocolo' => $protocolo
      ]);
    }

    return $response;
  }


  /**
   * Cancela um evento de Conciliação Financeira - ECONF
   *
   * @param string $referencia
   * @param string $protocolo
   * @return Response
   */
  public function cancelaEconf(string $referencia, string $protocolo): Response
  {
    $response = Http::withHeaders([
      'Authorization' => 'Basic ' . base64_encode($this->token),
    ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/econf/$protocolo");

    if ($response->failed()) {
      Log::error('FocusNFe.NFCe: Erro ao cancelar ECONF', [
        'response' => $response->json(),
        'referencia' => $referencia,
        'protocolo' => $protocolo
      ]);
    }

    return $response;
  }
}
