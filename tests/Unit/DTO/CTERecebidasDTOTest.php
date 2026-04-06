<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CTERecebidasDTO;

class CTERecebidasDTOTest extends TestCase
{
    public function test_from_array_cria_dto_com_observacoes(): void
    {
        $dto = CTERecebidasDTO::fromArray([
            'observacoes' => 'Manifestacao realizada com sucesso',
        ]);

        $this->assertInstanceOf(CTERecebidasDTO::class, $dto);
        $this->assertSame('Manifestacao realizada com sucesso', $dto->observacoes);
    }

    public function test_to_array_serializa_observacoes(): void
    {
        $dto = new CTERecebidasDTO('Observacao teste');

        $this->assertSame(['observacoes' => 'Observacao teste'], $dto->toArray());
    }
}
