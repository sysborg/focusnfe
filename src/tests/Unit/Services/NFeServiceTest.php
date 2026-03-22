<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Carbon\Carbon;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\Services\NFe;

class NFeServiceTest extends TestCase
{
    private NFe $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $ref = 'nfe-001';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => $this->baseUrl],
            ],
        ]));
        $container->instance('log', new class () {
            public function error(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });
        $container->instance('http', new HttpFactory());
        Container::setInstance($container);
        Facade::setFacadeApplication($container);

        $this->service = new NFe('test-token', 'production');
    }

    private function makeDto(): NFeDTO
    {
        return NFeDTO::fromArray([
            'natureza_operacao' => 'Venda de produto',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'tipo_documento' => 1,
            'local_destino' => 1,
            'finalidade_emissao' => 1,
            'consumidor_final' => 1,
            'presenca_comprador' => 1,
            'cnpj_emitente' => '07504505000132',
            'inscricao_estadual_emitente' => '111111111111',
            'logradouro_emitente' => 'Rua Teste',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'São Paulo',
            'uf_emitente' => 'SP',
            'regime_tributario_emitente' => 1,
            'nome_destinatario' => 'Cliente Teste',
            'cpf_destinatario' => '12345678909',
            'logradouro_destinatario' => 'Rua Cliente',
            'numero_destinatario' => '200',
            'bairro_destinatario' => 'Bairro',
            'municipio_destinatario' => 'São Paulo',
            'uf_destinatario' => 'SP',
            'indicador_inscricao_estadual_destinatario' => 9,
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

    public function test_envia_nfe_com_sucesso(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::response([
                'status' => 'processando_autorizacao',
                'ref' => $this->ref,
            ], 202),
        ]);

        $response = $this->service->envia($this->makeDto(), $this->ref);

        $this->assertEquals(202, $response->status());
        $this->assertEquals('processando_autorizacao', $response->json('status'));
    }

    public function test_get_nfe_autorizada(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref => Http::response([
                'status' => 'autorizado',
                'ref' => $this->ref,
                'chave_nfe' => '35260107504505000132550010000000011234567890',
            ], 200),
        ]);

        $response = $this->service->get($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('autorizado', $response->json('status'));
    }

    public function test_cancela_nfe(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref => Http::response([
                'status' => 'cancelado',
                'ref' => $this->ref,
            ], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('cancelado', $response->json('status'));
    }

    public function test_carta_correcao(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref . '/carta_correcao' => Http::response([
                'status' => 'autorizado',
                'mensagem_sefaz' => 'Evento registrado e vinculado a NF-e',
            ], 200),
        ]);

        $response = $this->service->cartaCorrecao($this->ref, ['correcao' => 'Correção de dado']);

        $this->assertEquals(200, $response->status());
    }

    public function test_inutilizacoes(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/inutilizacoes' => Http::response([], 200),
        ]);

        $response = $this->service->inutilizacoes();

        $this->assertEquals(200, $response->status());
    }

    public function test_reenvia_email(): void
    {
        $email = 'cliente@exemplo.com';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/$email" => Http::response([
                'status' => 'email_reenviado',
            ], 200),
        ]);

        $response = $this->service->reenviaEmail($this->ref, $email);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('email_reenviado', $response->json('status'));
    }

    public function test_insucesso_entrega(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/insucesso_entrega" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->insucessoEntrega($this->ref, [
            'data_tentativa' => '2026-01-15',
            'numero_tentativas' => 1,
            'motivo' => 1,
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_ator_interessado(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/ator_interessado" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->atorInteressado($this->ref, [
            'tipo_ator' => 1,
            'cnpj' => '07504505000132',
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_prorrogacao_icms(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/prorrogacao_icms" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->prorrogacaoIcms($this->ref, ['tipo' => 1]);

        $this->assertEquals(200, $response->status());
    }

    public function test_registra_econf(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf" => Http::response([
                'protocolo' => 'ECONF-001',
                'status' => 'autorizado',
            ], 201),
        ]);

        $response = $this->service->registraEconf($this->ref, [
            'forma_pagamento' => '01',
            'valor' => 100.0,
        ]);

        $this->assertEquals(201, $response->status());
        $this->assertEquals('ECONF-001', $response->json('protocolo'));
    }

    public function test_consulta_econf(): void
    {
        $protocolo = 'ECONF-001';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf/$protocolo" => Http::response([
                'protocolo' => $protocolo,
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->consultaEconf($this->ref, $protocolo);

        $this->assertEquals(200, $response->status());
        $this->assertEquals($protocolo, $response->json('protocolo'));
    }

    public function test_cancela_econf(): void
    {
        $protocolo = 'ECONF-001';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf/$protocolo" => Http::response([
                'status' => 'cancelado',
            ], 200),
        ]);

        $response = $this->service->cancelaEconf($this->ref, $protocolo);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('cancelado', $response->json('status'));
    }

    public function test_nfe_dto_inclui_formas_pagamento_no_payload(): void
    {
        $dto = $this->makeDto();
        $payload = $dto->toArray();

        $this->assertArrayHasKey('formas_pagamento', $payload);
        $this->assertNotEmpty($payload['formas_pagamento']);
        $this->assertEquals('01', $payload['formas_pagamento'][0]['forma_pagamento']);
    }

    public function test_nfe_dto_inclui_campos_opcionais_quando_informados(): void
    {
        $dto = NFeDTO::fromArray(array_merge($this->makeDto()->toArray(), [
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'cep_emitente' => '01001000',
            'email_emitente' => 'emitente@empresa.com',
            'informacoes_adicionais_contribuinte' => 'Pedido #123',
            'documentos_referenciados' => [['chave_nfe' => '35260107504505000132550010000000011234567890']],
        ]));

        $payload = $dto->toArray();

        $this->assertEquals('01001000', $payload['cep_emitente']);
        $this->assertEquals('emitente@empresa.com', $payload['email_emitente']);
        $this->assertEquals('Pedido #123', $payload['informacoes_adicionais_contribuinte']);
        $this->assertNotEmpty($payload['documentos_referenciados']);
    }
}
