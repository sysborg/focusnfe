<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeDTO;

class NFeDTOTest extends TestCase
{
    private function makeItens(): array
    {
        return [[
            'numero_item' => 1,
            'codigo_produto' => 'PROD001',
            'descricao' => 'Produto Teste',
            'codigo_ncm' => '84713012',
            'cfop' => '5102',
            'unidade_comercial' => 'UN',
            'quantidade_comercial' => 1,
            'valor_unitario_comercial' => 100.00,
            'valor_total_bruto' => 100.00,
            'icms_situacao_tributaria' => '400',
            'icms_origem' => 0,
            'pis_situacao_tributaria' => '07',
            'cofins_situacao_tributaria' => '07',
        ]];
    }

    private function makeBaseData(): array
    {
        return [
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
            'municipio_emitente' => 'Sao Paulo',
            'uf_emitente' => 'SP',
            'regime_tributario_emitente' => 1,
            'nome_destinatario' => 'Cliente Teste',
            'cpf_destinatario' => '12345678909',
            'logradouro_destinatario' => 'Rua Cliente',
            'numero_destinatario' => '200',
            'bairro_destinatario' => 'Bairro',
            'municipio_destinatario' => 'Sao Paulo',
            'uf_destinatario' => 'SP',
            'indicador_inscricao_estadual_destinatario' => 9,
            'itens' => $this->makeItens(),
            'formas_pagamento' => [
                ['forma_pagamento' => '01', 'valor_pagamento' => 100.0],
            ],
        ];
    }

    public function test_from_array_cria_dto_com_campos_basicos(): void
    {
        $dto = NFeDTO::fromArray($this->makeBaseData());

        $this->assertInstanceOf(NFeDTO::class, $dto);
        $this->assertSame('Venda de produto', $dto->natureza_operacao);
        $this->assertSame('07504505000132', $dto->cnpj_emitente);
        $this->assertSame('', $dto->cpf_emitente);
        $this->assertCount(1, $dto->itens);
        $this->assertCount(1, $dto->formas_pagamento);
    }

    public function test_from_array_aceita_cpf_emitente_quando_informado(): void
    {
        $data = $this->makeBaseData();
        unset($data['cnpj_emitente']);
        $data['cnpj_emitente'] = '';
        $data['cpf_emitente'] = '12345678909';

        $dto = NFeDTO::fromArray($data);

        $this->assertSame('12345678909', $dto->cpf_emitente);
        $this->assertSame('', $dto->cnpj_emitente);
    }

    public function test_to_array_serializa_data_e_campos_opcionais(): void
    {
        $dto = NFeDTO::fromArray(array_merge($this->makeBaseData(), [
            'complemento_emitente' => 'Sala 1',
            'cep_emitente' => '01001000',
            'telefone_emitente' => '11999999999',
            'email_emitente' => 'emitente@empresa.com',
            'nome_fantasia_emitente' => 'Empresa Teste',
            'complemento_destinatario' => 'Apto 2',
            'cep_destinatario' => '02002000',
            'telefone_destinatario' => '11888888888',
            'email_destinatario' => 'cliente@empresa.com',
            'nome_fantasia_destinatario' => 'Cliente Teste',
            'inscricao_municipal_destinatario' => '12345',
            'modalidade_frete' => 0,
            'transporte' => ['cnpj_transportador' => '12345678000190'],
            'valor_total_produtos' => 100.00,
            'valor_frete' => 10.00,
            'valor_total_nota' => 110.00,
            'informacoes_adicionais_contribuinte' => 'Pedido 123',
            'informacoes_adicionais_fisco' => 'Obs fisco',
            'documentos_referenciados' => [['chave_nfe' => '35260107504505000132550010000000011234567890']],
            'indicador_intermed_transacao' => '1',
            'cnpj_intermediador' => '12345678000190',
            'id_cadastro_intermediador' => 'INT-1',
        ]));

        $payload = $dto->toArray();

        $this->assertSame('2026-01-15T13:00:00+00:00', $payload['data_emissao']);
        $this->assertSame('Sala 1', $payload['complemento_emitente']);
        $this->assertSame('01001000', $payload['cep_emitente']);
        $this->assertSame('cliente@empresa.com', $payload['email_destinatario']);
        $this->assertSame(0, $payload['modalidade_frete']);
        $this->assertSame(['cnpj_transportador' => '12345678000190'], $payload['transporte']);
        $this->assertSame(110.0, $payload['valor_total_nota']);
        $this->assertSame('Pedido 123', $payload['informacoes_adicionais_contribuinte']);
        $this->assertSame('1', $payload['indicador_intermed_transacao']);
    }

    public function test_to_array_mantem_nomes_aderentes_ao_manual(): void
    {
        $payload = NFeDTO::fromArray($this->makeBaseData())->toArray();

        $this->assertArrayHasKey('cnpj_emitente', $payload);
        $this->assertArrayHasKey('presenca_comprador', $payload);
        $this->assertArrayHasKey('formas_pagamento', $payload);
        $this->assertArrayHasKey('logradouro_emitente', $payload);
        $this->assertArrayHasKey('logradouro_destinatario', $payload);
        $this->assertArrayNotHasKey('cnpjEmitente', $payload);
        $this->assertArrayNotHasKey('formasPagamento', $payload);
    }

    public function test_data_emissao_e_carbon_no_dto(): void
    {
        $dto = NFeDTO::fromArray($this->makeBaseData());

        $this->assertInstanceOf(Carbon::class, $dto->data_emissao);
    }
}
