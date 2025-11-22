<?php

namespace Sysborg\FocusNFe\app\Services;

use Illuminate\Support\Facades\Http;
use Log;

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
     * Realiza a consulta de um e-mail
     * 
     * @param string $email
     * @return array
     */
    public function get(string $email): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$email");

        if ($request->failed()) {
            Log::error('FocusNFe.ConsultarEmails: Erro ao consultar e-mail', [
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

    /**
     * Realiza a deleção de um e-mail
     * 
     * @param string $email
     * @return array  
     */
    public function delete(string $email): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$email");

        $data = $response->json();

        if ($response->failed()) {
            Log::error('FocusNFe.ConsultarEmails: Erro ao deletar e-mail', [
                'response' => $data,
                'email' => $email
            ]);

            if (isset($data['codigo']) && $data['codigo'] === 'requisicao_invalida') {
                return [
                    'erro' => 'Falha ao excluir o e-mail.',
                    'mensagem' => $data['mensagem'] ?? 'Erro desconhecido.',
                    'status_http' => 400
                ];
            }

            return [
                'erro' => 'Falha ao deletar o e-mail da lista de bloqueios.',
                'mensagem' => $data['mensagem'] ?? 'Erro desconhecido.',
                'status_http' => $response->status()
            ];
        }

        return [
            'status' => 'sucesso',
            'mensagem' => 'E-mail removido da lista de bloqueios.'
        ];
    }
}
