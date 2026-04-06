<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class TomadorDTOTest extends TestCase
{
    use BootstrapsFacadesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    private function getEnderecoValido(): EnderecoDTO
    {
        return new EnderecoDTO(
            logradouro: 'Rua Dias da Rocha Filho',
            numero: '999',
            complemento: 'Prédio 04 - Sala 34C',
            bairro: 'Alto da XV',
            codigoMunicipio: '4106902',
            uf: 'PR',
            cep: '80045165'
        );
    }

    public function test_cria_tomador_dto_com_cnpj(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            razaoSocial: 'Acras Tecnologia da Informação LTDA',
            endereco: $endereco,
            cnpj: '07504505000132',
            email: 'contato@focusnfe.com.br',
        );

        $this->assertEquals('07504505000132', $tomador->cnpj);
        $this->assertEquals('Acras Tecnologia da Informação LTDA', $tomador->razaoSocial);
        $this->assertEquals('contato@focusnfe.com.br', $tomador->email);
        $this->assertInstanceOf(EnderecoDTO::class, $tomador->endereco);
    }

    public function test_cria_tomador_dto_com_cpf(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            razaoSocial: 'João da Silva',
            endereco: $endereco,
            cpf: '12345678901',
            email: 'joao@exemplo.com',
        );

        $this->assertEquals('12345678901', $tomador->cpf);
        $this->assertNull($tomador->cnpj);
    }

    public function test_cria_tomador_dto_com_campos_opcionais(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            razaoSocial: 'Empresa Teste LTDA',
            endereco: $endereco,
            cnpj: '07504505000132',
            telefone: '41999999999',
            inscricaoMunicipal: '12345',
            nif: 'NIF123',
            motivoAusenciaNif: '1',
        );

        $this->assertEquals('41999999999', $tomador->telefone);
        $this->assertEquals('12345', $tomador->inscricaoMunicipal);
        $this->assertEquals('NIF123', $tomador->nif);
        $this->assertEquals('1', $tomador->motivoAusenciaNif);
    }

    public function test_cria_tomador_dto_from_array_com_snake_case(): void
    {
        $data = [
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
        ];

        $tomador = TomadorDTO::fromArray($data);

        $this->assertInstanceOf(TomadorDTO::class, $tomador);
        $this->assertEquals('07504505000132', $tomador->cnpj);
        $this->assertInstanceOf(EnderecoDTO::class, $tomador->endereco);
    }

    public function test_cria_tomador_dto_from_array_com_campos_opcionais(): void
    {
        $data = [
            'cnpj' => '07504505000132',
            'razao_social' => 'Empresa Teste',
            'telefone' => '41999999999',
            'inscricao_municipal' => '12345',
            'nif' => 'NIF123',
            'motivo_ausencia_nif' => '1',
            'endereco' => [
                'logradouro' => 'Rua Teste',
                'numero' => '1',
                'complemento' => '',
                'bairro' => 'Bairro Teste',
                'codigo_municipio' => '4106902',
                'uf' => 'PR',
                'cep' => '80000000'
            ]
        ];

        $tomador = TomadorDTO::fromArray($data);

        $this->assertEquals('41999999999', $tomador->telefone);
        $this->assertEquals('12345', $tomador->inscricaoMunicipal);
        $this->assertEquals('NIF123', $tomador->nif);
        $this->assertEquals('1', $tomador->motivoAusenciaNif);
    }

    public function test_to_array_inclui_cnpj_e_email(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            razaoSocial: 'Acras Tecnologia da Informação LTDA',
            endereco: $endereco,
            cnpj: '07504505000132',
            email: 'contato@focusnfe.com.br',
        );

        $array = $tomador->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('razao_social', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('endereco', $array);
        $this->assertEquals('07504505000132', $array['cnpj']);
        $this->assertIsArray($array['endereco']);
    }

    public function test_to_array_omite_campos_nulos(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            razaoSocial: 'Empresa Sem Email LTDA',
            endereco: $endereco,
            cnpj: '07504505000132',
        );

        $array = $tomador->toArray();

        $this->assertArrayNotHasKey('cpf', $array);
        $this->assertArrayNotHasKey('email', $array);
        $this->assertArrayNotHasKey('telefone', $array);
    }
}
