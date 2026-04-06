<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ModalRodoviarioOsDTO;

class ModalRodoviarioOsDTOTest extends TestCase
{
    public function test_from_array_cria_modal_rodoviario_os_com_data_tipada(): void
    {
        $dto = ModalRodoviarioOsDTO::fromArray([
            'taf' => 10,
            'numero_registro_estadual' => 20,
            'placa' => 'ABC1234',
            'renavam' => '12345678901',
            'cpf_proprietario' => 12345678901,
            'cnpj_proprietario' => 12345678000199,
            'taf_proprietario' => 30,
            'numero_registro_estadual_proprietario' => 40,
            'razao_social_proprietario' => 'Transportadora XPTO',
            'inscricao_estadual_proprietario' => '123456',
            'uf_proprietario' => 'PR',
            'tipo_proprietario' => 'T',
            'uf_licenciamento' => 'PR',
            'tipo_fretamento' => '1',
            'data_viagem_fretamento' => '2026-04-15',
        ]);

        $this->assertInstanceOf(ModalRodoviarioOsDTO::class, $dto);
        $this->assertSame('ABC1234', $dto->placa);
        $this->assertSame('PR', $dto->uf_licenciamento);
        $this->assertInstanceOf(Carbon::class, $dto->data_viagem_fretamento);
    }
}
