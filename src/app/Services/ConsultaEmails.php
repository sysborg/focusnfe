<?php

namespace Sysborg\FocusNFe\App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class ConsultaEmails {
  /**
   * URL base da API ConsultaEmails
   * 
   * @var string
   */
  const URL = '/v2/blocked_emails';

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
   * Realiza a consulta de um e-mail
   * 
   * @param string $email
   * @return array
   */
  public function get(string $email): array
{
    $request = Http::withHeaders([
        'Authorization' => $this->token,
    ])->get(config('focusnfe.URL.production') . self::URL . "/$email");

    if ($request->failed()) {
        Log::error('FocusNFe.Empresa: Erro ao consultar e-mail', [
            'response' => $request->json(),
            'data' => ['email' => $email]
        ]);
        return ['erro' => 'Falha na consulta do e-mail.'];
    }

    $data = $request->json();

    if (isset($data['block_type'])) {
        return [
            'status' => 'bloqueado',
            'email' => $data['email'],
            'block_type' => $data['block_type'],
            'bounce_type' => $data['bounce_type'] ?? null,
            'diagnostic_code' => $data['diagnostic_code'] ?? null,
            'blocked_at' => $data['blocked_at'] ?? null,
        ];
    }

    if (isset($data['codigo']) && $data['codigo'] === 'nao_encontrado') {
        return [
            'status' => 'nao_encontrado',
            'mensagem' => $data['mensagem']
        ];
    }

    return [
        'status' => 'desconhecido',
        'data' => $data
    ];
}


}
