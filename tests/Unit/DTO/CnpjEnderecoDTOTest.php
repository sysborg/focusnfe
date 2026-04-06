<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CnpjEnderecoDTO;

class CnpjEnderecoDTOTest extends TestCase
{
    public function test_from_array_cria_endereco_tipado(): void
    {
        $dto = CnpjEnderecoDTO::fromArray([
            'codigo_municipio' => '7535',
            'codigo_siafi' => '7535',
            'codigo_ibge' => '4106902',
            'nome_municipio' => 'CURITIBA',
            'logradouro' => 'XV DE NOVEMBRO',
            'complemento' => 'Conjunto 602',
            'numero' => '1234',
            'bairro' => 'CENTRO',
            'cep' => '80060000',
            'uf' => 'PR',
        ]);

        $this->assertInstanceOf(CnpjEnderecoDTO::class, $dto);
        $this->assertSame('4106902', $dto->codigo_ibge);
        $this->assertSame('CURITIBA', $dto->nome_municipio);
        $this->assertSame('PR', $dto->uf);
    }

    public function test_to_array_mantem_campos_opcionais(): void
    {
        $dto = new CnpjEnderecoDTO(
            codigo_municipio: '7535',
            codigo_siafi: '7535',
            codigo_ibge: '4106902',
            nome_municipio: 'CURITIBA',
            logradouro: 'XV DE NOVEMBRO',
            complemento: 'Conjunto 602',
            numero: '1234',
            bairro: 'CENTRO',
            cep: '80060000',
            uf: 'PR',
        );

        $payload = $dto->toArray();

        $this->assertSame('7535', $payload['codigo_municipio']);
        $this->assertSame('4106902', $payload['codigo_ibge']);
        $this->assertSame('PR', $payload['uf']);
    }
}
