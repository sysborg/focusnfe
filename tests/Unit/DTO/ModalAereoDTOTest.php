<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ArtigosPerigososDTO;
use Sysborg\FocusNfe\app\DTO\ModalAereoDTO;

class ModalAereoDTOTest extends TestCase
{
    public function test_from_array_cria_modal_aereo_com_artigos_perigosos(): void
    {
        $dto = ModalAereoDTO::fromArray([
            'numero_minuta' => 10,
            'numero_operacional' => 20,
            'data_prevista_entrega' => '2026-04-10',
            'dimensao_carga' => '10x10x10',
            'informacoes_manuseio' => ['frágil'],
            'classe_tarifa' => 'A',
            'codigo_tarifa' => '123',
            'valor_tarifa' => 55.4,
            'artigos_perigosos' => [
                [
                    'numero_onu' => '1090',
                    'quantidade_total_volumes' => '10',
                    'quantidade_total_artigos' => 5.5,
                    'unidade_medida' => 'KG',
                ],
            ],
        ]);

        $this->assertInstanceOf(ModalAereoDTO::class, $dto);
        $this->assertInstanceOf(Carbon::class, $dto->data_prevista_entrega);
        $this->assertInstanceOf(ArtigosPerigososDTO::class, $dto->artigos_perigosos[0]);
        $this->assertSame('1090', $dto->artigos_perigosos[0]->numero_onu);
    }
}
