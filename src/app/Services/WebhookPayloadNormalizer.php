<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Support\Facades\Event;
use Sysborg\FocusNfe\app\Events\HooksReceived;

/**
 * Helper para normalizar payloads de webhook recebidos pela aplicação consumidora.
 *
 * O package nao expõe camada HTTP para recepção; este helper existe apenas para
 * padronizar os dados antes de disparar o evento HooksReceived.
 */
class WebhookPayloadNormalizer
{
    /**
     * Normaliza um payload de webhook preservando os dados originais.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public static function normalize(array $payload): array
    {
        return [
            'evento' => $payload['evento'] ?? $payload['event'] ?? $payload['tipo_evento'] ?? null,
            'cnpj_emitente' => $payload['cnpj_emitente'] ?? $payload['cnpjEmitente'] ?? null,
            'referencia' => $payload['referencia'] ?? $payload['ref'] ?? null,
            'chave' => $payload['chave'] ?? $payload['chave_nfe'] ?? $payload['chaveNfe'] ?? null,
            'raw' => $payload,
        ];
    }

    /**
     * Normaliza o payload e dispara o evento HooksReceived.
     *
     * @param array<string, mixed> $payload
     */
    public static function dispatch(array $payload, string $source = 'focusnfe:webhook'): void
    {
        Event::dispatch(new HooksReceived(self::normalize($payload), $source));
    }
}
