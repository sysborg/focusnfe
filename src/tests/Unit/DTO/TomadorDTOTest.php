<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use InvalidArgumentException;

class TomadorDTOTest extends TestCase
{
    private function getEnderecoValido(): EnderecoDTO
    {
        return new EnderecoDTO(
            logradouro: 'Rua Dias da Rocha Filho',
            numero: '999',
            complemento: 'Prédio 04 - Sala 34C',
            bairro: 'Alto da XV',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80045165'
        );
    }

    /**
     * Testa criação de TomadorDTO com dados válidos
     */
    public function test_cria_tomador_dto_com_dados_validos(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: 'contato@focusnfe.com.br',
            endereco: $endereco
        );

        $this->assertEquals('07504505000132', $tomador->cnpj);
        $this->assertEquals('Acras Tecnologia da Informação LTDA', $tomador->razao_social);
        $this->assertEquals('contato@focusnfe.com.br', $tomador->email);
        $this->assertInstanceOf(EnderecoDTO::class, $tomador->endereco);
    }

    /**
     * Testa criação de TomadorDTO a partir de array
     */
    public function test_cria_tomador_dto_from_array(): void
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

    /**
     * Testa validação de CNPJ vazio
     */
    public function test_valida_cnpj_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo cnpj é obrigatório');

        $endereco = $this->getEnderecoValido();

        new TomadorDTO(
            cnpj: '',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: 'contato@focusnfe.com.br',
            endereco: $endereco
        );
    }

    /**
     * Testa validação de razão social vazia
     */
    public function test_valida_razao_social_vazia(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo razao_social é obrigatório');

        $endereco = $this->getEnderecoValido();

        new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: '',
            email: 'contato@focusnfe.com.br',
            endereco: $endereco
        );
    }

    /**
     * Testa validação de email vazio
     */
    public function test_valida_email_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo email é obrigatório e deve ser válido');

        $endereco = $this->getEnderecoValido();

        new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: '',
            endereco: $endereco
        );
    }

    /**
     * Testa validação de email inválido
     */
    public function test_valida_email_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo email é obrigatório e deve ser válido');

        $endereco = $this->getEnderecoValido();

        new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: 'email-invalido',
            endereco: $endereco
        );
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $endereco = $this->getEnderecoValido();

        $tomador = new TomadorDTO(
            cnpj: '07504505000132',
            razao_social: 'Acras Tecnologia da Informação LTDA',
            email: 'contato@focusnfe.com.br',
            endereco: $endereco
        );

        $array = $tomador->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('razao_social', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('endereco', $array);
        $this->assertEquals('07504505000132', $array['cnpj']);
    }
}
