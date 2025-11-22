<?php 

namespace Sysborg\FocusNFe\app\Services;

use Illuminate\Support\Facades\Http;
use Log;

class CNAE
{
    /**
     * URL base da API CNAE
     */
    const URL = '/v2/codigos_cnae';

    /**
     * Token de acesso
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
     */
    public function __construct(string $token, string $ambiente)
    {
        $this->token = $token;
        $this->ambiente = $ambiente;
    }

    /**
     * Lista todos os CNAEs 
     */
    public function list(int $offset = 1): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "?offset=$offset");

        $data = $request->json();

        if ($request->failed() || isset($data['codigo']) && $data['codigo'] === 'nao_encontrado') {
            Log::error('FocusNFe.CNAE: Erro ao listar CNAEs', [
                'response' => $data,
                'offset' => $offset
            ]);

            return [
                'erro' => 'Falha ao listar os CNAEs.',
                'mensagem' => $data['mensagem'] ?? 'Erro desconhecido.',
                'status_http' => $request->status()
            ];
        }

        return $data;
    }

 
    public function get(string $codigo): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->token),
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$codigo");

        $data = $request->json();

        if ($request->failed() || isset($data['codigo']) && $data['codigo'] === 'nao_encontrado') {
            Log::error('FocusNFe.CNAE: Erro ao buscar CNAE', [
                'response' => $data,
                'codigo' => $codigo
            ]);

            return [
                'erro' => 'Falha ao buscar o CNAE.',
                'mensagem' => $data['mensagem'] ?? 'Erro desconhecido.',
                'status_http' => $request->status()
            ];
        }

        return $data;
    }
}
