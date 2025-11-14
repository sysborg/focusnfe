<?php

namespace Sysborg\FocusNFe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;
use InvalidArgumentException;

class ServicoDTOTest extends TestCase
{
    /**
     * Testa criação de ServicoDTO com dados válidos
     */
    public function test_cria_servico_dto_com_dados_validos(): void
    {
        $servico = new ServicoDTO(
            aliquota: 5.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00,
            codigo_cnae: '6201-5/00'
        );

        $this->assertEquals(5.00, $servico->aliquota);
        $this->assertEquals('Serviços de consultoria', $servico->discriminacao);
        $this->assertFalse($servico->iss_retido);
        $this->assertEquals('0107', $servico->item_lista_servico);
        $this->assertEquals('620910000', $servico->codigo_tributario_municipio);
        $this->assertEquals(1000.00, $servico->valor_servicos);
        $this->assertEquals('6201-5/00', $servico->codigo_cnae);
    }

    /**
     * Testa criação de ServicoDTO sem código CNAE
     */
    public function test_cria_servico_dto_sem_codigo_cnae(): void
    {
        $servico = new ServicoDTO(
            aliquota: 5.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00
        );

        $this->assertNull($servico->codigo_cnae);
    }

    /**
     * Testa criação de ServicoDTO a partir de array
     */
    public function test_cria_servico_dto_from_array(): void
    {
        $data = [
            'aliquota' => 3.00,
            'discriminacao' => 'Serviços de TI',
            'iss_retido' => false,
            'item_lista_servico' => '0107',
            'codigo_tributario_municipio' => '620910000',
            'valor_servicos' => 500.00,
            'codigo_cnae' => '6201-5/00'
        ];

        $servico = ServicoDTO::fromArray($data);

        $this->assertInstanceOf(ServicoDTO::class, $servico);
        $this->assertEquals(3.00, $servico->aliquota);
        $this->assertEquals('6201-5/00', $servico->codigo_cnae);
    }

    /**
     * Testa criação de ServicoDTO a partir de array sem codigo_cnae
     */
    public function test_cria_servico_dto_from_array_sem_codigo_cnae(): void
    {
        $data = [
            'aliquota' => 3.00,
            'discriminacao' => 'Serviços de TI',
            'iss_retido' => false,
            'item_lista_servico' => '0107',
            'codigo_tributario_municipio' => '620910000',
            'valor_servicos' => 500.00
        ];

        $servico = ServicoDTO::fromArray($data);

        $this->assertInstanceOf(ServicoDTO::class, $servico);
        $this->assertNull($servico->codigo_cnae);
    }

    /**
     * Testa validação de alíquota negativa
     */
    public function test_valida_aliquota_negativa(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A alíquota deve estar entre 0 e 100');

        new ServicoDTO(
            aliquota: -1.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00
        );
    }

    /**
     * Testa validação de alíquota maior que 100
     */
    public function test_valida_aliquota_maior_que_100(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A alíquota deve estar entre 0 e 100');

        new ServicoDTO(
            aliquota: 101.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00
        );
    }

    /**
     * Testa validação de discriminação vazia
     */
    public function test_valida_discriminacao_vazia(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo discriminacao é obrigatório');

        new ServicoDTO(
            aliquota: 5.00,
            discriminacao: '',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00
        );
    }

    /**
     * Testa validação de valor de serviços zero
     */
    public function test_valida_valor_servicos_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O valor dos serviços deve ser maior que zero');

        new ServicoDTO(
            aliquota: 5.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 0.00
        );
    }

    /**
     * Testa validação de valor de serviços negativo
     */
    public function test_valida_valor_servicos_negativo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O valor dos serviços deve ser maior que zero');

        new ServicoDTO(
            aliquota: 5.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: -100.00
        );
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $servico = new ServicoDTO(
            aliquota: 5.00,
            discriminacao: 'Serviços de consultoria',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1000.00,
            codigo_cnae: '6201-5/00'
        );

        $array = $servico->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('aliquota', $array);
        $this->assertArrayHasKey('discriminacao', $array);
        $this->assertArrayHasKey('codigo_cnae', $array);
        $this->assertEquals(5.00, $array['aliquota']);
        $this->assertEquals('6201-5/00', $array['codigo_cnae']);
    }
}
