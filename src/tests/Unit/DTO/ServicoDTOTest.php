<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;

class ServicoDTOTest extends TestCase
{
    public function test_cria_servico_dto_com_campos_opcionais_de_ibs_cbs(): void
    {
        $servico = new ServicoDTO(
            aliquota: 5.0,
            discriminacao: 'Servicos de consultoria',
            issRetido: false,
            itemListaServico: '0107',
            codigoTributarioMunicipio: '620910000',
            valorServicos: 1000.0,
            codigoCnae: '6201-5/00',
            codigoNbs: '1.0107',
            codigoIndicadorOperacao: '2',
            ibsCbsClassificacaoTributaria: '100',
            ibsCbsSituacaoTributaria: '000',
            ibsCbsBaseCalculo: 1000.0,
            ibsUfAliquota: 0.1,
            ibsMunAliquota: 0.1,
            cbsAliquota: 0.9,
            ibsUfValor: 1.0,
            ibsMunValor: 1.0,
            cbsValor: 9.0
        );

        $payload = $servico->toArray();

        $this->assertSame('1.0107', $payload['codigo_nbs']);
        $this->assertSame('2', $payload['codigo_indicador_operacao']);
        $this->assertSame(1000.0, $payload['ibs_cbs_base_calculo']);
        $this->assertSame(9.0, $payload['cbs_valor']);
    }

    public function test_from_array_aceita_camel_case_e_snake_case(): void
    {
        $servico = ServicoDTO::fromArray([
            'aliquota' => 3.0,
            'discriminacao' => 'Servicos de TI',
            'iss_retido' => false,
            'item_lista_servico' => '0107',
            'codigo_tributario_municipio' => '620910000',
            'valor_servicos' => 500.0,
            'codigo_nbs' => '1.0107',
            'ibs_cbs_base_calculo' => 500,
            'cbs_valor' => 4.5,
        ]);

        $this->assertInstanceOf(ServicoDTO::class, $servico);
        $this->assertSame('1.0107', $servico->codigoNbs);
        $this->assertSame(500.0, $servico->ibsCbsBaseCalculo);
        $this->assertSame(4.5, $servico->cbsValor);
    }

    public function test_valida_aliquota_negativa(): void
    {
        $this->expectException(ValidationException::class);

        new ServicoDTO(
            aliquota: -1.0,
            discriminacao: 'Servicos de consultoria',
            issRetido: false,
            itemListaServico: '0107',
            codigoTributarioMunicipio: '620910000',
            valorServicos: 1000.0
        );
    }
}
