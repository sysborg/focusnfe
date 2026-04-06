<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;
use Sysborg\FocusNfe\app\Services\NFCe;

class NFCeServiceTest extends TestCase
{
    private NFCe $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $ref = 'nfce-001';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => $this->baseUrl],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
            ],
        ]));
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });
        $container->instance('http', new HttpFactory());
        $container->instance('events', new Dispatcher($container));

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        $this->service = new NFCe('test-token', 'production');
    }

    private function makeDto(): NFCeDTO
    {
        return NFCeDTO::fromArray([
            'natureza_operacao' => 'VENDA AO CONSUMIDOR',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'presenca_comprador' => 1,
            'modalidade_frete' => 9,
            'local_destino' => 1,
            'cnpj_emitente' => '07504505000132',
            'regime_tributario_emitente' => 1,
            'logradouro_emitente' => 'Rua Teste',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'Sao Paulo',
            'uf_emitente' => 'SP',
            'cep_emitente' => '01001000',
            'itens' => [[
                'numero_item' => 1,
                'codigo_produto' => 'P001',
                'descricao' => 'Produto',
                'codigo_ncm' => '84713012',
                'cfop' => '5102',
                'unidade_comercial' => 'UN',
                'quantidade_comercial' => 1,
                'valor_unitario_comercial' => 100.0,
                'valor_total_bruto' => 100.0,
                'icms_situacao_tributaria' => '400',
                'icms_origem' => 0,
                'pis_situacao_tributaria' => '07',
                'cofins_situacao_tributaria' => '07',
            ]],
            'formas_pagamento' => [
                ['forma_pagamento' => '01', 'valor_pagamento' => 100.0],
            ],
        ]);
    }

    public function test_envia_nfce_com_sucesso(): void
    {
        Http::fake([
            $this->baseUrl . NFCe::URL => Http::response([
                'status' => 'processando_autorizacao',
                'ref' => $this->ref,
            ], 202),
        ]);

        $response = $this->service->envia($this->makeDto());

        $this->assertSame(202, $response->status());
        $this->assertSame('processando_autorizacao', $response->json('status'));
    }

    public function test_get_nfce_autorizada(): void
    {
        Http::fake([
            $this->baseUrl . NFCe::URL . '/' . $this->ref => Http::response([
                'status' => 'autorizado',
                'ref' => $this->ref,
            ], 200),
        ]);

        $response = $this->service->get($this->ref);

        $this->assertSame(200, $response->status());
        $this->assertSame('autorizado', $response->json('status'));
    }

    public function test_cancela_nfce(): void
    {
        Http::fake([
            $this->baseUrl . NFCe::URL . '/' . $this->ref => Http::response([
                'status' => 'cancelado',
            ], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertSame(200, $response->status());
        $this->assertSame('cancelado', $response->json('status'));
    }

    public function test_inutilizacoes_nfce(): void
    {
        Http::fake([
            $this->baseUrl . NFCe::URL . '/inutilizacoes' => Http::response([], 200),
        ]);

        $response = $this->service->inutilizacoes();

        $this->assertSame(200, $response->status());
    }

    public function test_reenvia_email_nfce(): void
    {
        $email = 'cliente@exemplo.com';

        Http::fake([
            $this->baseUrl . NFCe::URL . "/{$this->ref}/{$email}" => Http::response([
                'status' => 'email_reenviado',
            ], 200),
        ]);

        $response = $this->service->reenviaEmail($this->ref, $email);

        $this->assertSame(200, $response->status());
        $this->assertSame('email_reenviado', $response->json('status'));
    }

    public function test_registra_econf_nfce(): void
    {
        Http::fake([
            $this->baseUrl . NFCe::URL . "/{$this->ref}/econf" => Http::response([
                'numero_protocolo' => '335250000000445',
                'status' => 'autorizado',
            ], 201),
        ]);

        $response = $this->service->registraEconf($this->ref, [
            'forma_pagamento' => '01',
            'valor' => 100.0,
        ]);

        $this->assertSame(201, $response->status());
        $this->assertSame('335250000000445', $response->json('numero_protocolo'));
    }

    public function test_consulta_econf_nfce(): void
    {
        $protocolo = '335250000000445';

        Http::fake([
            $this->baseUrl . NFCe::URL . "/{$this->ref}/econf/{$protocolo}" => Http::response([
                'numero_protocolo' => $protocolo,
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->consultaEconf($this->ref, $protocolo);

        $this->assertSame(200, $response->status());
        $this->assertSame($protocolo, $response->json('numero_protocolo'));
    }

    public function test_cancela_econf_nfce(): void
    {
        $protocolo = '335250000000445';

        Http::fake([
            $this->baseUrl . NFCe::URL . "/{$this->ref}/econf/{$protocolo}" => Http::response([
                'status' => 'cancelado',
            ], 200),
        ]);

        $response = $this->service->cancelaEconf($this->ref, $protocolo);

        $this->assertSame(200, $response->status());
        $this->assertSame('cancelado', $response->json('status'));
    }
}
