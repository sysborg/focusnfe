<?php

namespace Sysborg\FocusNfe\tests\Traits;

use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use Carbon\Carbon;

trait NFSeDataTrait
{
    /**
     * Retorna um PrestadorDTO válido para testes
     */
    protected function getPrestadorValido(): PrestadorDTO
    {
        return new PrestadorDTO(
            cnpj: '18765499000199',
            inscricaoMunicipal: '12345',
            codigoMunicipio: '3516200'
        );
    }

    /**
     * Retorna um TomadorDTO válido para testes
     */
    protected function getTomadorValido(): TomadorDTO
    {
        $endereco = new EnderecoDTO(
            logradouro: 'Rua Dias da Rocha Filho',
            numero: '999',
            complemento: 'Prédio 04 - Sala 34C',
            bairro: 'Alto da XV',
            codigoMunicipio: '4106902',
            uf: 'PR',
            cep: '80045165'
        );

        return new TomadorDTO(
            razaoSocial: 'Acras Tecnologia da Informação LTDA',
            endereco: $endereco,
            cnpj: '07504505000132',
            email: 'contato@focusnfe.com.br',
        );
    }

    /**
     * Retorna um ServicoDTO válido para testes
     */
    protected function getServicoValido(): ServicoDTO
    {
        return new ServicoDTO(
            aliquota: 3.00,
            discriminacao: 'Nota fiscal referente a serviços prestados',
            issRetido: false,
            itemListaServico: '0107',
            codigoTributarioMunicipio: '620910000',
            valorServicos: 1.00,
            codigoCnae: '6201-5/00'
        );
    }

    /**
     * Retorna um NFSeDTO válido para testes
     */
    protected function getNFSeValido(): NFSeDTO
    {
        return new NFSeDTO(
            dataEmissao: Carbon::parse('2017-09-21T22:15:00'),
            prestador: $this->getPrestadorValido(),
            tomador: $this->getTomadorValido(),
            servico: $this->getServicoValido()
        );
    }

    /**
     * Retorna dados de array válido de NFSe
     */
    protected function getNFSeArrayValido(): array
    {
        return [
            'data_emissao' => '2017-09-21T22:15:00',
            'prestador' => [
                'cnpj' => '18765499000199',
                'inscricao_municipal' => '12345',
                'codigo_municipio' => '3516200'
            ],
            'tomador' => [
                'cnpj' => '07504505000132',
                'razao_social' => 'Acras Tecnologia da Informação LTDA',
                'email' => 'contato@focusnfe.com.br',
                'endereco' => [
                    'logradouro' => 'Rua Dias da Rocha Filho',
                    'numero' => '999',
                    'complemento' => 'Prédio 04 - Sala 34C',
                    'bairro' => 'Alto da XV',
                    'codigo_municipio' => '4106902',
                    'uf' => 'PR',
                    'cep' => '80045165'
                ]
            ],
            'servico' => [
                'aliquota' => 3.00,
                'discriminacao' => 'Nota fiscal referente a serviços prestados',
                'iss_retido' => false,
                'item_lista_servico' => '0107',
                'codigo_tributario_municipio' => '620910000',
                'valor_servicos' => 1.00,
                'codigo_cnae' => '6201-5/00',
                'codigo_nbs' => '1.0107',
                'codigo_indicador_operacao' => '2',
                'ibs_cbs_classificacao_tributaria' => '100',
                'ibs_cbs_situacao_tributaria' => '000',
                'ibs_cbs_base_calculo' => 1.00,
                'ibs_uf_aliquota' => 0.1,
                'ibs_mun_aliquota' => 0.1,
                'cbs_aliquota' => 0.9,
                'ibs_uf_valor' => 0.001,
                'ibs_mun_valor' => 0.001,
                'cbs_valor' => 0.009
            ]
        ];
    }
}
