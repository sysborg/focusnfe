<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CTeDTO;
use Sysborg\FocusNfe\app\DTO\ModalRodoviarioDTO;

class CTeDTOTest extends TestCase
{
    private function makeData(): array
    {
        return [
            'referencia' => 'cte-001',
            'cfop' => '5353',
            'natureza_operacao' => 'PREST. DE SERV. TRANSPORTE',
            'tipo_cte' => '0',
            'data_emissao' => '2018-06-18T09:17:00',
            'codigo_municipio_envio' => '2927408',
            'municipio_envio' => 'Salvador',
            'uf_envio' => 'BA',
            'cnpj_emitente' => '51916585000125',
            'inscricao_estadual_emitente' => '12345678',
            'logradouro_emitente' => 'Aeroporto Internacional',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'Salvador',
            'uf_emitente' => 'BA',
            'cep_emitente' => '99880077',
            'telefone_emitente' => '4133336666',
            'email_emitente' => 'fiscal@acme.test',
            'nome_emitente' => 'ACME LTDA',
            'nome_fantasia_emitente' => 'ACME',
            'cpf_remetente' => '08111727908',
            'nome_remetente' => 'Remetente Teste',
            'codigo_municipio_remetente' => '4119152',
            'municipio_remetente' => 'Pinhais',
            'uf_remetente' => 'PR',
            'cnpj_destinatario' => '00112222000149',
            'nome_destinatario' => 'Destinatario Teste',
            'codigo_municipio_destinatario' => '2927408',
            'municipio_destinatario' => 'Salvador',
            'uf_destinatario' => 'BA',
            'email_destinatario' => 'destinatario@acme.test',
            'cnpj_tomador' => '51966818092777',
            'nome_tomador' => 'Tomador Teste',
            'logradouro_tomador' => 'Rua Joao Dalegrave',
            'numero_tomador' => '1',
            'bairro_tomador' => 'Bacacheri',
            'municipio_tomador' => 'Curitiba',
            'uf_tomador' => 'PR',
            'cep_tomador' => '88991188',
            'codigo_municipio_tomador' => '4106902',
            'indicador_inscricao_estadual_tomador' => '9',
            'valor_total' => '1.00',
            'valor_receber' => '0.80',
            'valor_total_tributos' => '0.00',
            'valor_total_carga' => '25.00',
            'icms_situacao_tributaria' => '00',
            'icms_base_calculo' => '1.00',
            'icms_aliquota' => '17.00',
            'icms_valor' => '0.17',
            'issqn_base_calculo' => '0.00',
            'issqn_valor' => '0.00',
            'modal_rodoviario' => [
                'type' => 'CTe',
                'rntrc' => '12345678',
                'ordens_coleta_associados' => [],
            ],
        ];
    }

    public function test_from_array_cria_cte_dto_com_campos_expandidos(): void
    {
        $dto = CTeDTO::fromArray($this->makeData());

        $this->assertInstanceOf(CTeDTO::class, $dto);
        $this->assertSame('cte-001', $dto->referencia);
        $this->assertSame('5353', $dto->cfop);
        $this->assertSame('PREST. DE SERV. TRANSPORTE', $dto->natureza_operacao);
        $this->assertSame('0', $dto->tipo_cte);
        $this->assertSame('2018-06-18T09:17:00', $dto->data_emissao);
        $this->assertSame('51916585000125', $dto->cnpj_emitente);
        $this->assertSame('fiscal@acme.test', $dto->email_emitente);
        $this->assertSame('Remetente Teste', $dto->nome_remetente);
        $this->assertSame('Destinatario Teste', $dto->nome_destinatario);
        $this->assertSame('Tomador Teste', $dto->nome_tomador);
        $this->assertSame('0.80', $dto->valor_receber);
        $this->assertSame('17.00', $dto->icms_aliquota);
        $this->assertInstanceOf(ModalRodoviarioDTO::class, $dto->modal_rodoviario);
    }

    public function test_to_array_mantem_nomes_aderentes_ao_manual(): void
    {
        $payload = CTeDTO::fromArray($this->makeData())->toArray();

        $this->assertArrayHasKey('cfop', $payload);
        $this->assertArrayHasKey('natureza_operacao', $payload);
        $this->assertArrayHasKey('tipo_cte', $payload);
        $this->assertArrayHasKey('data_emissao', $payload);
        $this->assertArrayHasKey('cnpj_emitente', $payload);
        $this->assertArrayHasKey('email_emitente', $payload);
        $this->assertArrayHasKey('nome_remetente', $payload);
        $this->assertArrayHasKey('nome_destinatario', $payload);
        $this->assertArrayHasKey('nome_tomador', $payload);
        $this->assertArrayHasKey('valor_total', $payload);
        $this->assertArrayHasKey('valor_receber', $payload);
        $this->assertArrayHasKey('valor_total_carga', $payload);
        $this->assertArrayHasKey('icms_situacao_tributaria', $payload);
        $this->assertArrayHasKey('issqn_base_calculo', $payload);
        $this->assertArrayNotHasKey('naturezaOperacao', $payload);
    }
}
