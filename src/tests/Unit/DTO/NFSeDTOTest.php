<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use Carbon\Carbon;
use InvalidArgumentException;

class NFSeDTOTest extends TestCase
{
    private function getPrestadorValido(): PrestadorDTO
    {
        return new PrestadorDTO(
            cnpj: '18765499000199',
            inscricao_municipal: '12345',
            codigo_municipio: '3516200'
        );
    }

    private function getTomadorValido(): TomadorDTO
    {
        $endereco = new EnderecoDTO(
            logradouro: 'Rua Dias da Rocha Filho',
            numero: '999',
            complemento: 'Prédio 04 - Sala 34C',
            bairro: 'Alto da XV',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80045165'
        );

        return new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: 'contato@focusnfe.com.br',
            endereco: $endereco
        );
    }

    private function getServicoValido(): ServicoDTO
    {
        return new ServicoDTO(
            aliquota: 3.00,
            discriminacao: 'Nota fiscal referente a serviços prestados',
            iss_retido: false,
            item_lista_servico: '0107',
            codigo_tributario_municipio: '620910000',
            valor_servicos: 1.00,
            codigo_cnae: '6201-5/00'
        );
    }

    /**
     * Testa criação de NFSeDTO com dados válidos
     */
    public function test_cria_nfse_dto_com_dados_validos(): void
    {
        $dataEmissao = Carbon::now()->subDay();
        $prestador = $this->getPrestadorValido();
        $tomador = $this->getTomadorValido();
        $servico = $this->getServicoValido();

        $nfse = new NFSeDTO(
            data_emissao: $dataEmissao,
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );

        $this->assertEquals($dataEmissao, $nfse->data_emissao);
        $this->assertInstanceOf(PrestadorDTO::class, $nfse->prestador);
        $this->assertInstanceOf(TomadorDTO::class, $nfse->tomador);
        $this->assertInstanceOf(ServicoDTO::class, $nfse->servico);
    }

    /**
     * Testa criação de NFSeDTO a partir de array
     */
    public function test_cria_nfse_dto_from_array(): void
    {
        $data = [
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
                'codigo_cnae' => '6201-5/00'
            ]
        ];

        $nfse = NFSeDTO::fromArray($data);

        $this->assertInstanceOf(NFSeDTO::class, $nfse);
        $this->assertInstanceOf(Carbon::class, $nfse->data_emissao);
        $this->assertInstanceOf(PrestadorDTO::class, $nfse->prestador);
        $this->assertInstanceOf(TomadorDTO::class, $nfse->tomador);
        $this->assertInstanceOf(ServicoDTO::class, $nfse->servico);
        $this->assertEquals('6201-5/00', $nfse->servico->codigo_cnae);
    }

    /**
     * Testa validação de data de emissão futura
     */
    public function test_valida_data_emissao_futura(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A data de emissão não pode ser futura');

        $dataEmissao = Carbon::now()->addDay();
        $prestador = $this->getPrestadorValido();
        $tomador = $this->getTomadorValido();
        $servico = $this->getServicoValido();

        new NFSeDTO(
            data_emissao: $dataEmissao,
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $dataEmissao = Carbon::now()->subDay();
        $prestador = $this->getPrestadorValido();
        $tomador = $this->getTomadorValido();
        $servico = $this->getServicoValido();

        $nfse = new NFSeDTO(
            data_emissao: $dataEmissao,
            prestador: $prestador,
            tomador: $tomador,
            servico: $servico
        );

        $array = $nfse->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('data_emissao', $array);
        $this->assertArrayHasKey('prestador', $array);
        $this->assertArrayHasKey('tomador', $array);
        $this->assertArrayHasKey('servico', $array);
    }

    /**
     * Testa validação em cascata de DTOs aninhados
     */
    public function test_valida_cascata_dtos_aninhados(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $data = [
            'data_emissao' => '2017-09-21T22:15:00',
            'prestador' => [
                'cnpj' => '', // CNPJ vazio deve lançar exceção
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
                'valor_servicos' => 1.00
            ]
        ];

        NFSeDTO::fromArray($data);
    }
}
