<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use InvalidArgumentException;

class PrestadorDTOTest extends TestCase
{
    /**
     * Testa criação de PrestadorDTO com dados válidos
     */
    public function test_cria_prestador_dto_com_dados_validos(): void
    {
        $prestador = new PrestadorDTO(
            cnpj: '18765499000199',
            inscricao_municipal: '12345',
            codigo_municipio: '3516200'
        );

        $this->assertEquals('18765499000199', $prestador->cnpj);
        $this->assertEquals('12345', $prestador->inscricao_municipal);
        $this->assertEquals('3516200', $prestador->codigo_municipio);
    }

    /**
     * Testa criação de PrestadorDTO a partir de array
     */
    public function test_cria_prestador_dto_from_array(): void
    {
        $data = [
            'cnpj' => '18765499000199',
            'inscricao_municipal' => '12345',
            'codigo_municipio' => '3516200'
        ];

        $prestador = PrestadorDTO::fromArray($data);

        $this->assertInstanceOf(PrestadorDTO::class, $prestador);
        $this->assertEquals('18765499000199', $prestador->cnpj);
    }

    /**
     * Testa validação de CNPJ vazio
     */
    public function test_valida_cnpj_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo cnpj é obrigatório');

        new PrestadorDTO(
            cnpj: '',
            inscricao_municipal: '12345',
            codigo_municipio: '3516200'
        );
    }

    /**
     * Testa validação de inscrição municipal vazia
     */
    public function test_valida_inscricao_municipal_vazia(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo inscricao_municipal é obrigatório');

        new PrestadorDTO(
            cnpj: '18765499000199',
            inscricao_municipal: '',
            codigo_municipio: '3516200'
        );
    }

    /**
     * Testa validação de código do município vazio
     */
    public function test_valida_codigo_municipio_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O campo codigo_municipio é obrigatório');

        new PrestadorDTO(
            cnpj: '18765499000199',
            inscricao_municipal: '12345',
            codigo_municipio: ''
        );
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $prestador = new PrestadorDTO(
            cnpj: '18765499000199',
            inscricao_municipal: '12345',
            codigo_municipio: '3516200'
        );

        $array = $prestador->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('inscricao_municipal', $array);
        $this->assertArrayHasKey('codigo_municipio', $array);
        $this->assertEquals('18765499000199', $array['cnpj']);
    }
}
