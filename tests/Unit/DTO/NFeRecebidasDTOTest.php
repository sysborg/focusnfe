<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeRecebidasDTO;

class NFeRecebidasDTOTest extends TestCase
{
    public function test_from_array_cria_dto_com_tipo_e_justificativa(): void
    {
        $dto = NFeRecebidasDTO::fromArray([
            'tipo' => 'confirmacao',
            'justificativa' => 'Documento validado',
        ]);

        $this->assertInstanceOf(NFeRecebidasDTO::class, $dto);
        $this->assertSame('confirmacao', $dto->tipo);
        $this->assertSame('Documento validado', $dto->justificativa);
    }

    public function test_to_array_serializa_campos(): void
    {
        $dto = new NFeRecebidasDTO('confirmacao', 'Documento validado');

        $this->assertSame([
            'tipo' => 'confirmacao',
            'justificativa' => 'Documento validado',
        ], $dto->toArray());
    }
}
