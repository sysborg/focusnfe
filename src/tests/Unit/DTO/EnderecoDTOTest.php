<?php

namespace Sysborg\FocusNFe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;
use InvalidArgumentException;

class EnderecoDTOTest extends TestCase
{
    /**
     * Testa criação de EnderecoDTO com dados válidos
     */
    public function test_cria_endereco_dto_com_dados_validos(): void
    {
        $endereco = new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '123',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80000000'
        );

        $this->assertEquals('Rua Teste', $endereco->logradouro);
        $this->assertEquals('123', $endereco->numero);
        $this->assertEquals('Apto 45', $endereco->complemento);
        $this->assertEquals('Centro', $endereco->bairro);
        $this->assertEquals('4106902', $endereco->codigo_municipio);
        $this->assertEquals('PR', $endereco->uf);
        $this->assertEquals('80000000', $endereco->cep);
    }

    /**
     * Testa criação de EnderecoDTO a partir de array
     */
    public function test_cria_endereco_dto_from_array(): void
    {
        $data = [
            'logradouro' => 'Rua Teste',
            'numero' => '123',
            'complemento' => 'Apto 45',
            'bairro' => 'Centro',
            'codigo_municipio' => '4106902',
            'uf' => 'PR',
            'cep' => '80000000'
        ];

        $endereco = EnderecoDTO::fromArray($data);

        $this->assertInstanceOf(EnderecoDTO::class, $endereco);
        $this->assertEquals('Rua Teste', $endereco->logradouro);
    }

    /**
     * Testa validação de logradouro vazio
     */
    public function test_valida_logradouro_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo logradouro é obrigatório');

        new EnderecoDTO(
            logradouro: '',
            numero: '123',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80000000'
        );
    }

    /**
     * Testa validação de numero vazio
     */
    public function test_valida_numero_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo numero é obrigatório');

        new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80000000'
        );
    }

    /**
     * Testa validação de bairro vazio
     */
    public function test_valida_bairro_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo bairro é obrigatório');

        new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '123',
            complemento: 'Apto 45',
            bairro: '',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80000000'
        );
    }

    /**
     * Testa validação de UF inválida
     */
    public function test_valida_uf_invalida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo uf é obrigatório e deve ter 2 caracteres');

        new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '123',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PRR',
            cep: '80000000'
        );
    }

    /**
     * Testa validação de CEP vazio
     */
    public function test_valida_cep_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo cep é obrigatório');

        new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '123',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: ''
        );
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $endereco = new EnderecoDTO(
            logradouro: 'Rua Teste',
            numero: '123',
            complemento: 'Apto 45',
            bairro: 'Centro',
            codigo_municipio: '4106902',
            uf: 'PR',
            cep: '80000000'
        );

        $array = $endereco->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('logradouro', $array);
        $this->assertArrayHasKey('numero', $array);
        $this->assertEquals('Rua Teste', $array['logradouro']);
    }
}
