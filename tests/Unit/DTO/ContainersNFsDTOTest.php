<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ContainersNFsDTO;

class ContainersNFsDTOTest extends TestCase
{
    public function test_from_array_cria_dto_com_campos_esperados(): void
    {
        $dto = ContainersNFsDTO::fromArray([
            'serie' => '1',
            'numero_documento' => '123',
            'unidade_medida_rateada' => 10.5,
        ]);

        $this->assertInstanceOf(ContainersNFsDTO::class, $dto);
        $this->assertSame('1', $dto->serie);
        $this->assertSame('123', $dto->numero_documento);
        $this->assertSame(10.5, $dto->unidade_medida_rateada);
    }
}
