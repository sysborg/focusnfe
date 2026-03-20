<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Support\Facades\Log;

/**
 * Logger inteligente do FocusNFe com channel configurável,
 * múltiplos níveis e sanitização automática de dados sensíveis
 */
class FocusNfeLogger
{
    /**
     * Chaves sensíveis que serão mascaradas nos logs
     *
     * @var array<string>
     */
    private static array $sensitiveKeys = [
        'token',
        'authorization',
        'password',
        'senha',
        'cpf',
        'cnpj',
        'secret',
        'key',
        'credential',
        'api_key',
        'access_token',
    ];

    /**
     * Retorna o canal de log configurado
     *
     * @return string
     */
    private static function channel(): string
    {
        return config('focusnfe.log.channel', 'stack');
    }

    /**
     * Retorna o nível mínimo de log configurado
     *
     * @return string
     */
    private static function level(): string
    {
        return config('focusnfe.log.level', 'debug');
    }

    /**
     * Verifica se um nível de log deve ser registrado conforme configuração
     *
     * @param string $level
     * @return bool
     */
    private static function shouldLog(string $level): bool
    {
        $levels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];
        $configured = $levels[self::level()] ?? 0;
        $requested  = $levels[$level] ?? 0;

        return $requested >= $configured;
    }

    /**
     * Sanitiza os dados do contexto mascarando campos sensíveis recursivamente
     *
     * @param array<mixed> $data
     * @return array<mixed>
     */
    public static function sanitize(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitize($value);
            } elseif (is_string($key) && in_array(strtolower($key), self::$sensitiveKeys, true)) {
                $sanitized[$key] = '***REDACTED***';
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Registra uma mensagem de nível debug
     *
     * @param string $message
     * @param array<mixed> $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        if (self::shouldLog('debug')) {
            Log::channel(self::channel())->debug($message, self::sanitize($context));
        }
    }

    /**
     * Registra uma mensagem de nível info
     *
     * @param string $message
     * @param array<mixed> $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        if (self::shouldLog('info')) {
            Log::channel(self::channel())->info($message, self::sanitize($context));
        }
    }

    /**
     * Registra uma mensagem de nível warning
     *
     * @param string $message
     * @param array<mixed> $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        if (self::shouldLog('warning')) {
            Log::channel(self::channel())->warning($message, self::sanitize($context));
        }
    }

    /**
     * Registra uma mensagem de nível error
     *
     * @param string $message
     * @param array<mixed> $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        if (self::shouldLog('error')) {
            Log::channel(self::channel())->error($message, self::sanitize($context));
        }
    }
}
