<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ModalRodoviarioDTO;
use Sysborg\FocusNfe\app\DTO\OrdensColetaAssociadosDTO;

class ModalRodoviarioDTOTest extends TestCase
{
    public function test_from_array_cria_modal_rodoviario_com_ordens_tipadas(): void
    {
        $dto = ModalRodoviarioDTO::fromArray([
            'rntrc' => '12345678',
            'ordens_coleta_associados' => [
                [
                    'serie' => 'A1',
                    'numero_ordem_coleta' => 100,
                    'data_emissao' => '2026-04-01',
                    'cnpj' => '07504505000132',
                    'codigo_interno' => 'INT-01',
                    'inscricao_estadual' => '123456',
                    'uf' => 'PR',
                    'telefone' => '41999999999',
                ],
            ],
        ]);

        $this->assertInstanceOf(ModalRodoviarioDTO::class, $dto);
        $this->assertSame('12345678', $dto->rntrc);
        $this->assertInstanceOf(OrdensColetaAssociadosDTO::class, $dto->ordens_coleta_associados[0]);
    }

    public function test_from_array_aceita_sem_ordens(): void
    {
        $dto = ModalRodoviarioDTO::fromArray([
            'rntrc' => '12345678',
        ]);

        $this->assertNull($dto->ordens_coleta_associados);
    }
}
