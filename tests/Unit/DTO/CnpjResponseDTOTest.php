<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\CnpjEnderecoDTO;
use Sysborg\FocusNfe\app\DTO\CnpjResponseDTO;

class CnpjResponseDTOTest extends TestCase
{
    public function test_from_array_cria_resposta_tipada_com_endereco(): void
    {
        $dto = CnpjResponseDTO::fromArray([
            'razao_social' => 'ACRAS TECNOLOGIA DA INFORMACAO LTDA',
            'cnpj' => '07504505000132',
            'situacao_cadastral' => 'ativa',
            'cnae_principal' => '6209100',
            'optante_simples_nacional' => false,
            'optante_mei' => false,
            'endereco' => [
                'codigo_municipio' => '7535',
                'codigo_siafi' => '7535',
                'codigo_ibge' => '4106902',
                'nome_municipio' => 'CURITIBA',
                'logradouro' => 'XV DE NOVEMBRO',
                'complemento' => 'Conjunto 602',
                'numero' => '1234',
                'bairro' => 'CENTRO',
                'cep' => '80060000',
                'uf' => 'PR',
            ],
        ]);

        $this->assertInstanceOf(CnpjResponseDTO::class, $dto);
        $this->assertSame('07504505000132', $dto->cnpj);
        $this->assertFalse($dto->optante_mei);
        $this->assertInstanceOf(CnpjEnderecoDTO::class, $dto->endereco);
        $this->assertSame('4106902', $dto->endereco->codigo_ibge);
        $this->assertSame('PR', $dto->endereco->uf);
    }
}
