<?php

namespace Sysborg\FocusNFe\app\Services;

use Log;
use Illuminate\Support\Facades\Http;

/**
 * Classe responsável por manipular o MDF-e
 * 
 */
class MDFe
{
    /**
     * URL base da API MDF-e
     * 
     * @var string
     */
    const URL = '/v2/mdfe';

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
     * Envia um MDF-e para processamento
     * 
     * @param array $data
     * @return array
     */
    public function envia(array $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL, $data);

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao enviar MDF-e', [
                'response' => $request->json(),
                'data' => $data
            ]);
        }

        return $request->json();
    }

    /**
     * Consulta um MDF-e pelo ID
     * 
     * @param string $referencia
     * @return array
     */
    public function consulta(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->get(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao consultar MDF-e', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Cancela um MDF-e
     * 
     * @param string $referencia
     * @return array
     */
    public function cancela(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->delete(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia");

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao cancelar MDF-e', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }

    /**
     * Inclui um condutor no MDF-e
     * 
     * @param string $referencia
     * @param array $data
     * @return array
     */
    public function incluiCondutor(string $referencia, array $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/inclusao_condutor", $data);

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao incluir condutor no MDF-e', [
                'response' => $request->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $request->json();
    }

    /**
     * Inclui um DFe no MDF-e
     * 
     * @param string $referencia
     * @param array $data
     * @return array
     */
    public function incluiDFe(string $referencia, array $data): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/inclusao_dfe", $data);

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao incluir DFe no MDF-e', [
                'response' => $request->json(),
                'referencia' => $referencia,
                'data' => $data
            ]);
        }

        return $request->json();
    }

    /**
     * Encerra um MDF-e
     * 
     * @param string $referencia
     * @return array
     */
    public function encerra(string $referencia): array
    {
        $request = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->token,
        ])->post(config('focusnfe.URL.' . $this->ambiente) . self::URL . "/$referencia/encerrar");

        if ($request->failed()) {
            Log::error('FocusNFe.MDFe: Erro ao encerrar MDF-e', [
                'response' => $request->json(),
                'referencia' => $referencia
            ]);
        }

        return $request->json();
    }
}
