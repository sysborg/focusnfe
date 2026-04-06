<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\MDFeDTO;

class MDFeDTOTest extends TestCase
{
    private function makeBaseData(): array
    {
        return [
            'emitente' => 1,
            'serie' => 1,
            'numero' => 1,
            'uf_inicio' => 'BA',
            'uf_fim' => 'PR',
            'cnpj_emitente' => '22274451000119',
            'inscricao_estadual_emitente' => '25231737',
            'valor_total_carga' => '20000.00',
            'codigo_unidade_medida_peso_bruto' => '01',
        ];
    }

    public function test_cria_mdfe_dto_com_campos_obrigatorios(): void
    {
        $dto = MDFeDTO::fromArray($this->makeBaseData());

        $this->assertInstanceOf(MDFeDTO::class, $dto);
        $this->assertSame(1, $dto->emitente);
        $this->assertSame('BA', $dto->ufInicio);
        $this->assertSame('PR', $dto->ufFim);
        $this->assertSame('22274451000119', $dto->cnpjEmitente);
        $this->assertSame('20000.00', $dto->valorTotalCarga);
    }

    public function test_cria_mdfe_dto_com_campos_de_transporte(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'tipo_transporte' => '1',
            'modo_transporte' => '2',
            'data_emissao' => '2019-01-30',
            'data_hora_previsto_inicio_viagem' => '2019-01-16',
        ]);

        $dto = MDFeDTO::fromArray($data);

        $this->assertSame('1', $dto->tipoTransporte);
        $this->assertSame('2', $dto->modoTransporte);
        $this->assertSame('2019-01-30', $dto->dataEmissao);
        $this->assertSame('2019-01-16', $dto->dataHoraPrevistInicioViagem);
    }

    public function test_cria_mdfe_dto_com_municipios_e_percurso(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'municipios_carregamento' => [
                ['codigo' => '2927408', 'nome' => 'Salvador']
            ],
            'municipios_descarregamento' => [
                [
                    'codigo' => '4119152',
                    'nome' => 'Pinhais',
                    'conhecimentos_transporte' => [
                        ['chave_cte' => '21111100317911000149570011000000051123456786']
                    ]
                ]
            ],
            'percursos' => [
                ['uf_percurso' => 'PR']
            ],
            'quantidade_total_cte' => 1,
            'peso_bruto' => '11.0000',
        ]);

        $dto = MDFeDTO::fromArray($data);

        $this->assertCount(1, $dto->municipiosCarregamento);
        $this->assertSame('Salvador', $dto->municipiosCarregamento[0]['nome']);
        $this->assertCount(1, $dto->municipiosDescarregamento);
        $this->assertCount(1, $dto->percursos);
        $this->assertSame(1, $dto->quantidadeTotalCte);
        $this->assertSame('11.0000', $dto->pesoBruto);
    }

    public function test_cria_mdfe_dto_com_modal_aereo(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'modal_aereo' => [
                'marca_nacionalidade_aeronave' => 'ABCD',
                'marca_matricula_aeronave' => '123456',
                'numero_voo' => 'AB1234',
                'aerodromo_embarque' => 'OACI',
                'aerodromo_destino' => 'OACI',
                'data_voo' => '2018-06-15',
            ],
        ]);

        $dto = MDFeDTO::fromArray($data);

        $this->assertNotNull($dto->modalAereo);
        $this->assertSame('AB1234', $dto->modalAereo['numero_voo']);
        $this->assertNull($dto->modalRodoviario);
        $this->assertNull($dto->modalAquaviario);
        $this->assertNull($dto->modalFerroviario);
    }

    public function test_cria_mdfe_dto_com_modal_rodoviario(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'modal_rodoviario' => [
                'rntrc' => '12345678',
            ],
        ]);

        $dto = MDFeDTO::fromArray($data);

        $this->assertNotNull($dto->modalRodoviario);
        $this->assertSame('12345678', $dto->modalRodoviario['rntrc']);
    }

    public function test_to_array_converte_para_snake_case(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'tipo_transporte' => '1',
            'modo_transporte' => '2',
            'data_emissao' => '2019-01-30',
        ]);

        $dto = MDFeDTO::fromArray($data);
        $payload = $dto->toArray();

        $this->assertArrayHasKey('uf_inicio', $payload);
        $this->assertArrayHasKey('uf_fim', $payload);
        $this->assertArrayHasKey('cnpj_emitente', $payload);
        $this->assertArrayHasKey('inscricao_estadual_emitente', $payload);
        $this->assertArrayHasKey('valor_total_carga', $payload);
        $this->assertArrayHasKey('tipo_transporte', $payload);
        $this->assertArrayHasKey('modo_transporte', $payload);
        $this->assertArrayHasKey('data_emissao', $payload);
        $this->assertSame('1', $payload['tipo_transporte']);
    }

    public function test_to_array_serializa_data_hora_prevista(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'data_hora_previsto_inicio_viagem' => '2019-01-16T08:00:00',
        ]);

        $dto = MDFeDTO::fromArray($data);
        $payload = $dto->toArray();

        $this->assertArrayHasKey('data_hora_previsto_inicio_viagem', $payload);
        $this->assertSame('2019-01-16T08:00:00', $payload['data_hora_previsto_inicio_viagem']);
    }

    public function test_aceita_camel_case_na_entrada(): void
    {
        $dto = MDFeDTO::fromArray([
            'emitente' => 1,
            'serie' => 1,
            'numero' => 1,
            'ufInicio' => 'SP',
            'ufFim' => 'RJ',
            'cnpjEmitente' => '22274451000119',
            'inscricaoEstadualEmitente' => '25231737',
            'valorTotalCarga' => '5000.00',
            'codigoUnidadeMedidaPesoBruto' => '01',
        ]);

        $this->assertSame('SP', $dto->ufInicio);
        $this->assertSame('RJ', $dto->ufFim);
    }
}
