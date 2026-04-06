<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\ContainersDTO;
use Sysborg\FocusNfe\app\DTO\ContainersNFesDTO;
use Sysborg\FocusNfe\app\DTO\ContainersNFsDTO;

class ContainersDTOTest extends TestCase
{
    public function test_from_array_cria_container_com_nfs_e_nfes_tipados(): void
    {
        $dto = ContainersDTO::fromArray([
            'identificacao' => 'CONT-001',
            'lacres' => ['L1', 'L2'],
            'nfs' => [
                [
                    'serie' => '1',
                    'numero_documento' => '123',
                    'unidade_medida_rateada' => 10.5,
                ],
            ],
            'nfes' => [
                [
                    'chave_nfe' => '35260107504505000132550010000000011234567890',
                    'unidade_medida_rateada' => 12.3,
                ],
            ],
        ]);

        $this->assertInstanceOf(ContainersDTO::class, $dto);
        $this->assertSame('CONT-001', $dto->identificacao);
        $this->assertCount(2, $dto->lacres);
        $this->assertInstanceOf(ContainersNFsDTO::class, $dto->nfs[0]);
        $this->assertInstanceOf(ContainersNFesDTO::class, $dto->nfes[0]);
    }

    public function test_from_array_aceita_container_sem_documentos(): void
    {
        $dto = ContainersDTO::fromArray([
            'identificacao' => 'CONT-002',
            'lacres' => ['L1'],
        ]);

        $this->assertNull($dto->nfs);
        $this->assertNull($dto->nfes);
    }
}
