<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\RateLimiter as IlluminateRateLimiter;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Repository as CacheRepositoryContract;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Exceptions\RateLimitException;
use Sysborg\FocusNfe\app\Services\FocusNfeHttp;

class FocusNfeHttpTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'log' => ['channel' => 'stack', 'level' => 'debug'],
                'retry' => ['times' => 1, 'sleep' => 0],
                'rate_limit' => ['enabled' => false, 'max_attempts' => 60, 'decay_seconds' => 60],
            ],
        ]));
        $cacheRepository = new Repository(new ArrayStore());
        $container->instance('http', new HttpFactory());
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });
        $container->instance(CacheRepositoryContract::class, $cacheRepository);
        $container->instance('cache.store', $cacheRepository);
        $container->instance('cache', $cacheRepository);
        $container->instance('cache.rateLimiter', new IlluminateRateLimiter($cacheRepository));

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);
    }

    public function test_get_envia_header_de_autorizacao_e_query_string(): void
    {
        Http::fake([
            'https://api.focusnfe.com.br/v2/nfe/teste*' => Http::response(['ok' => true], 200),
        ]);

        $client = FocusNfeHttp::withToken('meu-token');
        $response = $client->get('https://api.focusnfe.com.br/v2/nfe/teste', ['completo' => 'true']);

        $this->assertTrue($response->ok());

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.focusnfe.com.br/v2/nfe/teste?completo=true'
                && $request->hasHeader('Authorization', 'Basic ' . base64_encode('meu-token'));
        });
    }

    public function test_post_put_delete_e_pending_usam_pending_request_configurado(): void
    {
        Http::fake([
            'https://api.focusnfe.com.br/*' => Http::response(['ok' => true], 200),
        ]);

        $client = FocusNfeHttp::withToken('abc123');

        $this->assertSame('Illuminate\\Http\\Client\\PendingRequest', $client->pending()::class);
        $this->assertTrue($client->post('https://api.focusnfe.com.br/post', ['nome' => 'teste'])->ok());
        $this->assertTrue($client->put('https://api.focusnfe.com.br/put', ['nome' => 'teste'])->ok());
        $this->assertTrue($client->delete('https://api.focusnfe.com.br/delete', ['id' => 1])->ok());

        Http::assertSentCount(3);
    }

    public function test_lanca_excecao_quando_rate_limit_e_excedido(): void
    {
        config()->set('focusnfe.rate_limit.enabled', true);
        config()->set('focusnfe.rate_limit.max_attempts', 1);
        config()->set('focusnfe.rate_limit.decay_seconds', 60);

        Http::fake([
            'https://api.focusnfe.com.br/*' => Http::response(['ok' => true], 200),
        ]);

        $client = FocusNfeHttp::withToken('abc123');
        $client->get('https://api.focusnfe.com.br/primeira');

        $this->expectException(RateLimitException::class);

        $client->get('https://api.focusnfe.com.br/segunda');
    }
}
