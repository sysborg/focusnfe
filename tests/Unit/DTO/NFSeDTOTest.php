<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class NFSeDTOTest extends TestCase
{
    use BootstrapsFacadesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    private function getPrestadorValido(): PrestadorDTO
    {
        return new PrestadorDTO(
            cnpj: '11222333000181',
            inscricaoMunicipal: '12345',
            codigoMunicipio: '3516200'
        );
    }

    private function getTomadorValido(): TomadorDTO
    {
        return new TomadorDTO(
            razaoSocial: 'Acras Tecnologia da Informacao LTDA',
            endereco: new EnderecoDTO(
                logradouro: 'Rua Dias da Rocha Filho',
                numero: '999',
                complemento: 'Predio 04 - Sala 34C',
                bairro: 'Alto da XV',
                codigoMunicipio: '4106902',
                uf: 'PR',
                cep: '80045165'
            ),
            cnpj: '07504505000132',
            email: 'contato@focusnfe.com.br',
        );
    }

    private function getServicoValido(): ServicoDTO
    {
        return new ServicoDTO(
            aliquota: 3.0,
            discriminacao: 'Nota fiscal referente a servicos prestados',
            issRetido: false,
            itemListaServico: '0107',
            codigoTributarioMunicipio: '620910000',
            valorServicos: 1.0,
            codigoCnae: '6201-5/00'
        );
    }

    public function test_cria_nfse_dto_com_dados_validos(): void
    {
        $dataEmissao = Carbon::now()->subDay();

        $nfse = new NFSeDTO(
            dataEmissao: $dataEmissao,
            prestador: $this->getPrestadorValido(),
            tomador: $this->getTomadorValido(),
            servico: $this->getServicoValido()
        );

        $this->assertEquals($dataEmissao, $nfse->dataEmissao);
        $this->assertInstanceOf(PrestadorDTO::class, $nfse->prestador);
        $this->assertInstanceOf(TomadorDTO::class, $nfse->tomador);
        $this->assertInstanceOf(ServicoDTO::class, $nfse->servico);
    }

    public function test_cria_nfse_dto_from_array_com_snake_case(): void
    {
        $data = [
            'data_emissao' => '2017-09-21T22:15:00',
            'prestador' => [
                'cnpj' => '11222333000181',
                'inscricao_municipal' => '12345',
                'codigo_municipio' => '3516200',
            ],
            'tomador' => [
                'cnpj' => '07504505000132',
                'razao_social' => 'Acras Tecnologia da Informacao LTDA',
                'email' => 'contato@focusnfe.com.br',
                'endereco' => [
                    'logradouro' => 'Rua Dias da Rocha Filho',
                    'numero' => '999',
                    'complemento' => 'Predio 04 - Sala 34C',
                    'bairro' => 'Alto da XV',
                    'codigo_municipio' => '4106902',
                    'uf' => 'PR',
                    'cep' => '80045165',
                ],
            ],
            'servico' => [
                'aliquota' => 3.00,
                'discriminacao' => 'Nota fiscal referente a servicos prestados',
                'iss_retido' => false,
                'item_lista_servico' => '0107',
                'codigo_tributario_municipio' => '620910000',
                'valor_servicos' => 1.00,
                'codigo_cnae' => '6201-5/00',
                'codigo_nbs' => '1.0107',
            ],
        ];

        $nfse = NFSeDTO::fromArray($data);

        $this->assertInstanceOf(NFSeDTO::class, $nfse);
        $this->assertSame('6201-5/00', $nfse->servico->codigoCnae);
        $this->assertSame('1.0107', $nfse->servico->codigoNbs);
    }

    public function test_rejeita_data_emissao_futura(): void
    {
        $this->expectException(ValidationException::class);

        new NFSeDTO(
            dataEmissao: Carbon::now()->addDay(),
            prestador: $this->getPrestadorValido(),
            tomador: $this->getTomadorValido(),
            servico: $this->getServicoValido()
        );
    }

    public function test_to_array_formata_payload_com_servico(): void
    {
        $nfse = new NFSeDTO(
            dataEmissao: Carbon::now()->subDay(),
            prestador: $this->getPrestadorValido(),
            tomador: $this->getTomadorValido(),
            servico: new ServicoDTO(
                aliquota: 3.0,
                discriminacao: 'Servico com IBS/CBS',
                issRetido: false,
                itemListaServico: '0107',
                codigoTributarioMunicipio: '620910000',
                valorServicos: 100.0,
                codigoNbs: '1.0107',
                codigoIndicadorOperacao: '2',
                ibsCbsClassificacaoTributaria: '100',
                ibsCbsSituacaoTributaria: '000',
                ibsCbsBaseCalculo: 100,
                ibsUfAliquota: 0.1,
                ibsMunAliquota: 0.1,
                cbsAliquota: 0.9,
                ibsUfValor: 0.1,
                ibsMunValor: 0.1,
                cbsValor: 0.9
            )
        );

        $array = $nfse->toArray();

        $this->assertSame('1.0107', $array['servico']['codigo_nbs']);
        $this->assertSame('2', $array['servico']['codigo_indicador_operacao']);
        $this->assertSame(100.0, $array['servico']['ibs_cbs_base_calculo']);
        $this->assertSame(0.9, $array['servico']['cbs_valor']);
    }
}
