<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ArtigosPerigososDTO;

class ArtigosPerigososDTOTest extends TestCase
{
    public function test_from_array_cria_dto_com_campos_esperados(): void
    {
        $dto = ArtigosPerigososDTO::fromArray([
            'numero_onu' => '1090',
            'quantidade_total_volumes' => '10',
            'quantidade_total_artigos' => 15.5,
            'unidade_medida' => 'KG',
        ]);

        $this->assertInstanceOf(ArtigosPerigososDTO::class, $dto);
        $this->assertSame('1090', $dto->numero_onu);
        $this->assertSame(15.5, $dto->quantidade_total_artigos);
        $this->assertSame('KG', $dto->unidade_medida);
    }

    public function test_to_array_mantem_chaves_esperadas(): void
    {
        $dto = new ArtigosPerigososDTO('1090', '10', 15.5, 'KG');

        $payload = $dto->toArray();

        $this->assertSame('1090', $payload['numero_onu']);
        $this->assertSame('10', $payload['quantidade_total_volumes']);
        $this->assertSame(15.5, $payload['quantidade_total_artigos']);
        $this->assertSame('KG', $payload['unidade_medida']);
    }
}
