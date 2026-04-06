<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\FerroviasDTO;

class FerroviasDTOTest extends TestCase
{
    public function test_from_array_cria_ferrovia_com_campos_esperados(): void
    {
        $dto = FerroviasDTO::fromArray([
            'cnpj' => '07504505000132',
            'codigo_interno' => 10,
            'inscricao_estadual' => 123456,
            'razao_social' => 'Ferrovia Teste',
            'logradouro' => 'Rua Um',
            'numero' => '100',
            'complemento' => 'Galpao',
            'bairro' => 'Centro',
            'codigo_municipio' => 4106902,
            'municipio' => 'Curitiba',
            'cep' => 80000000,
            'uf' => 'PR',
        ]);

        $this->assertInstanceOf(FerroviasDTO::class, $dto);
        $this->assertSame('07504505000132', $dto->cnpj);
        $this->assertSame(10, $dto->codigo_interno);
        $this->assertSame('Curitiba', $dto->municipio);
        $this->assertSame('PR', $dto->uf);
    }
}
