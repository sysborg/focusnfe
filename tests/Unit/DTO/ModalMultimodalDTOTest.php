<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ModalMultimodalDTO;

class ModalMultimodalDTOTest extends TestCase
{
    public function test_from_array_cria_modal_multimodal_com_campos_esperados(): void
    {
        $dto = ModalMultimodalDTO::fromArray([
            'numero_certificado_operador' => 123.4,
            'indicador_negociavel' => 'S',
            'nome_seguradora' => 'Seguradora XPTO',
            'cnpj_seguradora' => '07504505000132',
            'numero_apolice_seguro' => 'AP-123',
            'numero_averbacao_seguro' => 'AV-456',
        ]);

        $this->assertInstanceOf(ModalMultimodalDTO::class, $dto);
        $this->assertSame(123.4, $dto->numero_certificado_operador);
        $this->assertSame('Seguradora XPTO', $dto->nome_seguradora);
        $this->assertSame('AV-456', $dto->numero_averbacao_seguro);
    }
}
