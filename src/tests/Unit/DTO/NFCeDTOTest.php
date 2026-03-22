<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;

class NFCeDTOTest extends TestCase
{
    private function makeItens(): array
    {
        return [
            [
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
            ],
        ];
    }

    private function makeFormasPagamento(): array
    {
        return [
            [
                'forma_pagamento' => '01', // 01=dinheiro
                'valor_pagamento' => 100.00,
            ],
        ];
    }

    private function makeMinimalData(): array
    {
        return [
            'natureza_operacao' => 'Venda ao Consumidor',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'presenca_comprador' => 1,
            'modalidade_frete' => 9,
            'local_destino' => 1,
            'cnpj_emitente' => '07504505000132',
            'regime_tributario_emitente' => 1,
            'logradouro_emitente' => 'Rua Teste',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'São Paulo',
            'uf_emitente' => 'SP',
            'cep_emitente' => '01001000',
            'itens' => $this->makeItens(),
            'formas_pagamento' => $this->makeFormasPagamento(),
        ];
    }

    public function test_cria_nfce_dto_com_campos_minimos(): void
    {
        $dto = NFCeDTO::fromArray($this->makeMinimalData());

        $this->assertInstanceOf(NFCeDTO::class, $dto);
        $this->assertEquals('Venda ao Consumidor', $dto->natureza_operacao);
        $this->assertEquals('07504505000132', $dto->cnpj_emitente);
        $this->assertEquals(1, $dto->regime_tributario_emitente);
        $this->assertCount(1, $dto->itens);
        $this->assertCount(1, $dto->formas_pagamento);
    }

    public function test_cria_nfce_dto_com_destinatario(): void
    {
        $data = $this->makeMinimalData();
        $data['cpf_destinatario'] = '12345678909';
        $data['nome_destinatario'] = 'João Silva';

        $dto = NFCeDTO::fromArray($data);

        $this->assertEquals('12345678909', $dto->cpf_destinatario);
        $this->assertEquals('João Silva', $dto->nome_destinatario);
    }

    public function test_destinatario_e_opcional(): void
    {
        $dto = NFCeDTO::fromArray($this->makeMinimalData());

        $this->assertNull($dto->cpf_destinatario);
        $this->assertNull($dto->nome_destinatario);
    }

    public function test_modalidade_frete_padrao_e_nove(): void
    {
        $data = $this->makeMinimalData();
        unset($data['modalidade_frete']);

        $dto = NFCeDTO::fromArray($data);

        $this->assertEquals(9, $dto->modalidade_frete);
    }

    public function test_local_destino_padrao_e_um(): void
    {
        $data = $this->makeMinimalData();
        unset($data['local_destino']);

        $dto = NFCeDTO::fromArray($data);

        $this->assertEquals(1, $dto->local_destino);
    }

    public function test_to_array_inclui_campos_obrigatorios(): void
    {
        $dto = NFCeDTO::fromArray($this->makeMinimalData());
        $payload = $dto->toArray();

        $this->assertArrayHasKey('natureza_operacao', $payload);
        $this->assertArrayHasKey('data_emissao', $payload);
        $this->assertArrayHasKey('cnpj_emitente', $payload);
        $this->assertArrayHasKey('regime_tributario_emitente', $payload);
        $this->assertArrayHasKey('cep_emitente', $payload);
        $this->assertArrayHasKey('itens', $payload);
        $this->assertArrayHasKey('formas_pagamento', $payload);
    }

    public function test_to_array_nao_inclui_campos_nulos(): void
    {
        $dto = NFCeDTO::fromArray($this->makeMinimalData());
        $payload = $dto->toArray();

        // Campos null devem aparecer como null no payload
        // (a API FocusNFe ignora campos null)
        $this->assertNull($payload['cpf_destinatario']);
        $this->assertNull($payload['cnpj_intermediador']);
    }

    public function test_cria_dto_com_multiplas_formas_pagamento(): void
    {
        $data = $this->makeMinimalData();
        $data['formas_pagamento'] = [
            ['forma_pagamento' => '01', 'valor_pagamento' => 50.00],
            ['forma_pagamento' => '03', 'valor_pagamento' => 50.00], // 03=cartão de crédito
        ];

        $dto = NFCeDTO::fromArray($data);

        $this->assertCount(2, $dto->formas_pagamento);
    }

    public function test_cria_dto_com_campos_intermediador(): void
    {
        $data = $this->makeMinimalData();
        $data['indicador_intermed_transacao'] = '1';
        $data['cnpj_intermediador'] = '12345678000190';
        $data['id_cadastro_intermediador'] = 'INTER123';

        $dto = NFCeDTO::fromArray($data);

        $this->assertEquals('1', $dto->indicador_intermed_transacao);
        $this->assertEquals('12345678000190', $dto->cnpj_intermediador);
        $this->assertEquals('INTER123', $dto->id_cadastro_intermediador);
    }

    public function test_cria_dto_com_totais_informados(): void
    {
        $data = $this->makeMinimalData();
        $data['valor_total_nota'] = 100.00;
        $data['valor_total_produtos'] = 100.00;
        $data['valor_desconto'] = 0.00;

        $dto = NFCeDTO::fromArray($data);

        $this->assertEquals(100.00, $dto->valor_total_nota);
        $this->assertEquals(100.00, $dto->valor_total_produtos);
        $this->assertEquals(0.00, $dto->valor_desconto);
    }

    public function test_data_emissao_e_instancia_carbon(): void
    {
        $dto = NFCeDTO::fromArray($this->makeMinimalData());

        $this->assertInstanceOf(Carbon::class, $dto->data_emissao);
    }

    public function test_cria_dto_via_construtor_direto(): void
    {
        $dto = new NFCeDTO(
            natureza_operacao: 'Venda ao Consumidor',
            data_emissao: Carbon::now(),
            presenca_comprador: 1,
            modalidade_frete: 9,
            local_destino: 1,
            cnpj_emitente: '07504505000132',
            regime_tributario_emitente: 1,
            logradouro_emitente: 'Rua Teste',
            numero_emitente: '100',
            bairro_emitente: 'Centro',
            municipio_emitente: 'São Paulo',
            uf_emitente: 'SP',
            cep_emitente: '01001000',
            itens: $this->makeItens(),
            formas_pagamento: $this->makeFormasPagamento(),
        );

        $this->assertInstanceOf(NFCeDTO::class, $dto);
        $this->assertNull($dto->cpf_destinatario);
    }
}
