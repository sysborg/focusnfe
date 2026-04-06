<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ContainersNFesDTO;

class ContainersNFesDTOTest extends TestCase
{
    public function test_from_array_cria_dto_com_campos_esperados(): void
    {
        $dto = ContainersNFesDTO::fromArray([
            'chave_nfe' => '35260107504505000132550010000000011234567890',
            'unidade_medida_rateada' => 12.3,
        ]);

        $this->assertInstanceOf(ContainersNFesDTO::class, $dto);
        $this->assertSame('35260107504505000132550010000000011234567890', $dto->chave_nfe);
        $this->assertSame(12.3, $dto->unidade_medida_rateada);
    }
}
