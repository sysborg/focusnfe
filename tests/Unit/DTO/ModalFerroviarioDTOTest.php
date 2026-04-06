<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\FerroviasDTO;
use Sysborg\FocusNfe\app\DTO\ModalFerroviarioDTO;

class ModalFerroviarioDTOTest extends TestCase
{
    public function test_from_array_cria_modal_ferroviario_com_ferrovias_tipadas(): void
    {
        $dto = ModalFerroviarioDTO::fromArray([
            'tipo_trafego' => 1,
            'responsavel_faturamento' => 2,
            'ferrovia_emitente' => 3,
            'valor_frete_trafego_mutuo' => 120.4,
            'chave_cte_ferrovia_origem' => '35260107504505000132570010000000011234567890',
            'ferrovias' => [
                [
                    'cnpj' => '07504505000132',
                    'codigo_interno' => 10,
                    'inscricao_estadual' => 123456,
                    'razao_social' => 'Ferrovia Teste',
                    'logradouro' => 'Rua Um',
                    'numero' => '100',
                    'complemento' => 'Galpao',
                    'bairro' => 'Centro',
                    'codigo_municipio' => 4106902,
                    'municipio' => 'Curitiba',
                    'cep' => 80000000,
                    'uf' => 'PR',
                ],
            ],
            'fluxo_ferroviario' => 'Leste-Oeste',
        ]);

        $this->assertInstanceOf(ModalFerroviarioDTO::class, $dto);
        $this->assertInstanceOf(FerroviasDTO::class, $dto->ferrovias[0]);
        $this->assertSame('Leste-Oeste', $dto->fluxo_ferroviario);
    }
}
