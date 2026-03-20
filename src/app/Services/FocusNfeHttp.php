<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Sysborg\FocusNfe\app\Exceptions\RateLimitException;

/**
 * Cliente HTTP inteligente do FocusNFe com autenticação,
 * retry com exponential backoff e proteção contra rate limits
 */
class FocusNfeHttp
{
    /**
     * Instância configurada do PendingRequest
     *
     * @var PendingRequest
     */
    private PendingRequest $http;

    /**
     * Construtor privado — use FocusNfeHttp::withToken()
     *
     * @param PendingRequest $http
     */
    private function __construct(PendingRequest $http)
    {
        $this->http = $http;
    }

    /**
     * Cria uma instância autenticada com retry e exponential backoff configurados
     *
     * @param string $token Token de acesso da API FocusNFe
     * @return self
     */
    public static function withToken(string $token): self
    {
        $times = (int) config('focusnfe.retry.times', 3);
        $sleep = (int) config('focusnfe.retry.sleep', 1000);

        $http = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($token),
        ])->retry(
            $times,
            static fn (int $attempt) => $sleep * (2 ** ($attempt - 1)),
            static fn (\Throwable $e) => $e instanceof ConnectionException
        );

        return new self($http);
    }

    /**
     * Verifica o rate limit antes de cada requisição
     *
     * @throws RateLimitException quando o limite de requisições por janela de tempo é atingido
     * @return void
     */
    private function checkRateLimit(): void
    {
        if (!config('focusnfe.rate_limit.enabled', true)) {
            return;
        }

        $maxAttempts  = (int) config('focusnfe.rate_limit.max_attempts', 60);
        $decaySeconds = (int) config('focusnfe.rate_limit.decay_seconds', 60);
        $executed     = false;

        RateLimiter::attempt(
            key: 'focusnfe-api',
            maxAttempts: $maxAttempts,
            callback: function () use (&$executed): void {
                $executed = true;
            },
            decaySeconds: $decaySeconds
        );

        if (!$executed) {
            $available = RateLimiter::availableIn('focusnfe-api');
            FocusNfeLogger::warning('FocusNfe: Rate limit excedido', [
                'available_in_seconds' => $available,
            ]);
            throw new RateLimitException(
                "Rate limit excedido para a API FocusNFe. Tente novamente em {$available} segundo(s)."
            );
        }
    }

    /**
     * Realiza uma requisição GET
     *
     * @param string $url
     * @param array<mixed> $query
     * @return Response
     */
    public function get(string $url, array $query = []): Response
    {
        $this->checkRateLimit();
        return $this->http->get($url, $query);
    }

    /**
     * Realiza uma requisição POST com corpo JSON
     *
     * @param string $url
     * @param array<mixed> $data
     * @return Response
     */
    public function post(string $url, array $data = []): Response
    {
        $this->checkRateLimit();
        return $this->http->post($url, $data);
    }

    /**
     * Realiza uma requisição PUT
     *
     * @param string $url
     * @param array<mixed> $data
     * @return Response
     */
    public function put(string $url, array $data = []): Response
    {
        $this->checkRateLimit();
        return $this->http->put($url, $data);
    }

    /**
     * Realiza uma requisição DELETE
     *
     * @param string $url
     * @param array<mixed> $data
     * @return Response
     */
    public function delete(string $url, array $data = []): Response
    {
        $this->checkRateLimit();
        return $this->http->delete($url, $data);
    }

    /**
     * Retorna o PendingRequest configurado para operações especiais (ex: upload de arquivo)
     *
     * @return PendingRequest
     */
    public function pending(): PendingRequest
    {
        $this->checkRateLimit();
        return $this->http;
    }
}
