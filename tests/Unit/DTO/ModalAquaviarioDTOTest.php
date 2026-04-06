<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ContainersDTO;
use Sysborg\FocusNfe\app\DTO\ModalAquaviarioDTO;

class ModalAquaviarioDTOTest extends TestCase
{
    public function test_from_array_cria_modal_aquaviario_com_containers_tipados(): void
    {
        $dto = ModalAquaviarioDTO::fromArray([
            'valor_prestacao' => 100.5,
            'adicional_frete_renovacao_marinha' => 20.3,
            'identificacao_navio' => 'NAVIO-01',
            'balsas' => ['BALSA-1'],
            'numero_viagem' => 7,
            'direcao' => 'N',
            'irin_navio' => 'IRIN123',
            'containers' => [
                [
                    'identificacao' => 'CONT-001',
                    'lacres' => ['L1'],
                ],
            ],
            'tipo_navegacao' => '1',
        ]);

        $this->assertInstanceOf(ModalAquaviarioDTO::class, $dto);
        $this->assertSame('NAVIO-01', $dto->identificacao_navio);
        $this->assertInstanceOf(ContainersDTO::class, $dto->containers[0]);
        $this->assertSame('1', $dto->tipo_navegacao);
    }
}
