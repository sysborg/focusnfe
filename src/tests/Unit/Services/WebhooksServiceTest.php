<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\WebhookDTO;
use Sysborg\FocusNfe\app\Services\Webhooks;
use Sysborg\FocusNfe\tests\Traits\MockHttpResponseTrait;
use Illuminate\Support\Facades\Http;

class WebhooksServiceTest extends TestCase
{
    use MockHttpResponseTrait;

    private Webhooks $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new \Illuminate\Container\Container();
        $container->instance('config', new \Illuminate\Config\Repository([
            'focusnfe' => [
                'URL' => ['production' => $this->baseUrl],
            ],
        ]));
        $container->instance('log', new class () {
            public function error(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });
        \Illuminate\Container\Container::setInstance($container);
        \Illuminate\Support\Facades\Facade::setFacadeApplication($container);
        $container->instance('http', new \Illuminate\Http\Client\Factory());

        $this->service = new Webhooks('test-token', 'production');
    }

    private function makeDto(string $evento = 'nfe_autorizada'): WebhookDTO
    {
        return new WebhookDTO(
            cnpj_emitente: '07504505000132',
            url: 'https://meuapp.com/webhooks/nfe',
            evento: $evento,
        );
    }

    public function test_cadastrar_webhook_com_sucesso(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL => Http::response([
                'id' => 1,
                'cnpj_emitente' => '07504505000132',
                'url' => 'https://meuapp.com/webhooks/nfe',
                'evento' => 'nfe_autorizada',
            ], 201),
        ]);

        $response = $this->service->cadastrar($this->makeDto());

        $this->assertEquals(201, $response->status());
        $this->assertEquals(1, $response->json('id'));
        $this->assertEquals('nfe_autorizada', $response->json('evento'));
    }

    public function test_cadastrar_webhook_com_erro(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL => Http::response([
                'codigo' => 'requisicao_invalida',
                'mensagem' => 'URL inválida',
            ], 422),
        ]);

        $response = $this->service->cadastrar($this->makeDto());

        $this->assertTrue($response->failed());
        $this->assertEquals('requisicao_invalida', $response->json('codigo'));
    }

    public function test_listar_webhooks(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL => Http::response([
                ['id' => 1, 'evento' => 'nfe_autorizada'],
                ['id' => 2, 'evento' => 'nfe_cancelada'],
            ], 200),
        ]);

        $response = $this->service->listar();

        $this->assertEquals(200, $response->status());
        $this->assertCount(2, $response->json());
    }

    public function test_consultar_webhook_por_id(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL . '/1' => Http::response([
                'id' => 1,
                'cnpj_emitente' => '07504505000132',
                'url' => 'https://meuapp.com/webhooks/nfe',
                'evento' => 'nfe_autorizada',
            ], 200),
        ]);

        $response = $this->service->consultar(1);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, $response->json('id'));
    }

    public function test_consultar_webhook_inexistente(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL . '/999' => Http::response([
                'codigo' => 'nao_encontrado',
                'mensagem' => 'Webhook não encontrado',
            ], 404),
        ]);

        $response = $this->service->consultar(999);

        $this->assertTrue($response->failed());
        $this->assertEquals(404, $response->status());
    }

    public function test_atualizar_webhook(): void
    {
        $dto = new WebhookDTO(
            cnpj_emitente: '07504505000132',
            url: 'https://meuapp.com/webhooks/nfe-novo',
            evento: 'nfe_cancelada',
        );

        Http::fake([
            $this->baseUrl . Webhooks::URL . '/1' => Http::response([
                'id' => 1,
                'url' => 'https://meuapp.com/webhooks/nfe-novo',
                'evento' => 'nfe_cancelada',
            ], 200),
        ]);

        $response = $this->service->atualizar(1, $dto);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('nfe_cancelada', $response->json('evento'));
    }

    public function test_remover_webhook(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL . '/1' => Http::response(null, 204),
        ]);

        $response = $this->service->remover(1);

        $this->assertEquals(204, $response->status());
    }

    public function test_testar_webhook(): void
    {
        Http::fake([
            $this->baseUrl . Webhooks::URL . '/1/testar' => Http::response([
                'mensagem' => 'Payload de teste enviado com sucesso',
            ], 200),
        ]);

        $response = $this->service->testar(1);

        $this->assertEquals(200, $response->status());
    }

    public function test_webhook_dto_serializa_corretamente(): void
    {
        $dto = $this->makeDto('nfce_autorizada');
        $payload = $dto->toArray();

        $this->assertEquals('07504505000132', $payload['cnpj_emitente']);
        $this->assertEquals('https://meuapp.com/webhooks/nfe', $payload['url']);
        $this->assertEquals('nfce_autorizada', $payload['evento']);
    }

    public function test_webhook_dto_from_array(): void
    {
        $dto = WebhookDTO::fromArray([
            'cnpj_emitente' => '07504505000132',
            'url' => 'https://meuapp.com/hooks',
            'evento' => 'cte_autorizado',
        ]);

        $this->assertInstanceOf(WebhookDTO::class, $dto);
        $this->assertEquals('cte_autorizado', $dto->evento);
    }

    public function test_eventos_suportados_estao_definidos(): void
    {
        $this->assertContains('nfe_autorizada', WebhookDTO::EVENTOS);
        $this->assertContains('nfe_cancelada', WebhookDTO::EVENTOS);
        $this->assertContains('nfce_autorizada', WebhookDTO::EVENTOS);
        $this->assertContains('nfce_cancelada', WebhookDTO::EVENTOS);
        $this->assertContains('nfse_autorizada', WebhookDTO::EVENTOS);
        $this->assertContains('nfse_cancelada', WebhookDTO::EVENTOS);
        $this->assertContains('cte_autorizado', WebhookDTO::EVENTOS);
        $this->assertContains('cte_cancelado', WebhookDTO::EVENTOS);
        $this->assertContains('mdfe_autorizado', WebhookDTO::EVENTOS);
        $this->assertContains('mdfe_cancelado', WebhookDTO::EVENTOS);
    }
}
