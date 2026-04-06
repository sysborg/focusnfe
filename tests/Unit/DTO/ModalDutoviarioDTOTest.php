<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ModalDutoviarioDTO;

class ModalDutoviarioDTOTest extends TestCase
{
    public function test_from_array_cria_modal_dutoviario_com_datas(): void
    {
        $dto = ModalDutoviarioDTO::fromArray([
            'valor_tarifa' => 88.9,
            'data_inicio' => '2026-04-01',
            'data_fim' => '2026-04-30',
        ]);

        $this->assertInstanceOf(ModalDutoviarioDTO::class, $dto);
        $this->assertSame(88.9, $dto->valor_tarifa);
        $this->assertSame('2026-04-01', $dto->data_inicio);
        $this->assertInstanceOf(Carbon::class, $dto->data_fim);
    }
}
