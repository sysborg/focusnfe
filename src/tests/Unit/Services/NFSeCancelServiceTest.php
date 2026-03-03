<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as LogFacade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\NFSe;

class NFSeCancelServiceTest extends TestCase
{
    private NFSe $service;
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => [
                    'production' => 'https://api.focusnfe.com.br',
                ],
            ],
        ]));
        $container->instance('http', new HttpFactory());
        $container->instance('events', new class () {
            public function dispatch($event): void
            {
            }
        });
        $container->instance('log', new class () {
            public function error(string $message, array $context = []): void
            {
            }

            public function debug(string $message, array $context = []): void
            {
            }
        });

        Container::setInstance($container);
        Facade::setFacadeApplication($container);

        if (!class_exists('Log')) {
            class_alias(LogFacade::class, 'Log');
        }

        $this->service = new NFSe($this->token, $this->ambiente);
    }

    public function test_cancela_nfse_envia_justificativa_no_payload_delete(): void
    {
        $referencia = 'nfs-2';
        $justificativa = 'Teste de cancelamento de nota';
        $url = config('focusnfe.URL.production') . NFSe::URL . "/{$referencia}";

        Http::fake([
            $url => Http::response(['status' => 'cancelado'], 200),
        ]);

        $response = $this->service->cancela($referencia, $justificativa);

        $this->assertTrue($response->ok());
        $this->assertSame('cancelado', $response->json('status'));

        Http::assertSent(function ($request) use ($url, $justificativa) {
            return $request->url() === $url
                && $request->method() === 'DELETE'
                && $request['justificativa'] === $justificativa
                && $request->hasHeader('Authorization', 'Basic ' . base64_encode($this->token));
        });
    }
}
