<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\OrdensColetaAssociadosDTO;

class OrdensColetaAssociadosDTOTest extends TestCase
{
    public function test_from_array_cria_ordem_coleta_com_data_tipada(): void
    {
        $dto = OrdensColetaAssociadosDTO::fromArray([
            'serie' => 'A1',
            'numero_ordem_coleta' => 100,
            'data_emissao' => '2026-04-01',
            'cnpj' => '07504505000132',
            'codigo_interno' => 'INT-01',
            'inscricao_estadual' => '123456',
            'uf' => 'PR',
            'telefone' => '41999999999',
        ]);

        $this->assertInstanceOf(OrdensColetaAssociadosDTO::class, $dto);
        $this->assertSame('A1', $dto->serie);
        $this->assertSame(100, $dto->numero_ordem_coleta);
        $this->assertInstanceOf(Carbon::class, $dto->data_emissao);
        $this->assertSame('PR', $dto->uf);
    }
}
