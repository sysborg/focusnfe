<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;

class NFSeNDTOTest extends TestCase
{
    private function makeBaseData(): array
    {
        return [
            'data_emissao' => '2024-05-07T07:34:56-0300',
            'data_competencia' => '2024-05-07',
            'codigo_municipio_emissora' => 4106902,
            'cnpj_prestador' => '18765499000199',
            'inscricao_municipal_prestador' => '12345',
            'codigo_opcao_simples_nacional' => 2,
            'regime_especial_tributacao' => 0,
            'cnpj_tomador' => '07504505000132',
            'razao_social_tomador' => 'Acras Tecnologia da Informação LTDA',
            'codigo_municipio_tomador' => 4106902,
            'codigo_municipio_prestacao' => 4106902,
            'codigo_tributacao_nacional_iss' => '010701',
            'descricao_servico' => 'Nota emitida em caráter de TESTE',
            'valor_servico' => 1.00,
            'tributacao_iss' => 1,
            'tipo_retencao_iss' => 1,
        ];
    }

    public function test_cria_nfsen_dto_com_campos_obrigatorios(): void
    {
        $dto = NFSeNDTO::fromArray($this->makeBaseData());

        $this->assertInstanceOf(NFSeNDTO::class, $dto);
        $this->assertSame('2024-05-07T07:34:56-0300', $dto->dataEmissao);
        $this->assertSame('2024-05-07', $dto->dataCompetencia);
        $this->assertSame(4106902, $dto->codigoMunicipioEmissora);
        $this->assertSame('18765499000199', $dto->cnpjPrestador);
        $this->assertSame('12345', $dto->inscricaoMunicipalPrestador);
        $this->assertSame(2, $dto->codigoOpcaoSimplesNacional);
        $this->assertSame(0, $dto->regimeEspecialTributacao);
    }

    public function test_cria_nfsen_dto_com_dados_do_tomador(): void
    {
        $dto = NFSeNDTO::fromArray($this->makeBaseData());

        $this->assertSame('07504505000132', $dto->cnpjTomador);
        $this->assertSame('Acras Tecnologia da Informação LTDA', $dto->razaoSocialTomador);
        $this->assertSame(4106902, $dto->codigoMunicipioTomador);
    }

    public function test_cria_nfsen_dto_com_dados_do_servico(): void
    {
        $dto = NFSeNDTO::fromArray($this->makeBaseData());

        $this->assertSame(4106902, $dto->codigoMunicipioPrestacao);
        $this->assertSame('010701', $dto->codigoTributacaoNacionalIss);
        $this->assertSame('Nota emitida em caráter de TESTE', $dto->descricaoServico);
        $this->assertSame(1.00, $dto->valorServico);
        $this->assertSame(1, $dto->tributacaoIss);
        $this->assertSame(1, $dto->tipoRetencaoIss);
    }

    public function test_cria_nfsen_dto_com_campos_opcionais_do_tomador(): void
    {
        $data = array_merge($this->makeBaseData(), [
            'cep_tomador' => '80045165',
            'logradouro_tomador' => 'Rua Dias da Rocha Filho',
            'numero_tomador' => '999',
            'complemento_tomador' => 'Prédio 04 - Sala 34C',
            'bairro_tomador' => 'Alto da XV',
            'telefone_tomador' => '41 3256-8060',
            'email_tomador' => 'contato@focusnfe.com.br',
        ]);

        $dto = NFSeNDTO::fromArray($data);

        $this->assertSame('80045165', $dto->cepTomador);
        $this->assertSame('Rua Dias da Rocha Filho', $dto->logradouroTomador);
        $this->assertSame('999', $dto->numeroTomador);
        $this->assertSame('Prédio 04 - Sala 34C', $dto->complementoTomador);
        $this->assertSame('Alto da XV', $dto->bairroTomador);
        $this->assertSame('41 3256-8060', $dto->telefoneTomador);
        $this->assertSame('contato@focusnfe.com.br', $dto->emailTomador);
    }

    public function test_aceita_camel_case_na_entrada(): void
    {
        $dto = NFSeNDTO::fromArray([
            'dataEmissao' => '2024-05-07T07:34:56-0300',
            'dataCompetencia' => '2024-05-07',
            'codigoMunicipioEmissora' => 4106902,
            'cnpjPrestador' => '18765499000199',
            'inscricaoMunicipalPrestador' => '12345',
            'codigoOpcaoSimplesNacional' => 2,
            'regimeEspecialTributacao' => 0,
            'cnpjTomador' => '07504505000132',
            'razaoSocialTomador' => 'Empresa Teste',
            'codigoMunicipioTomador' => 4106902,
            'codigoMunicipioPrestacao' => 4106902,
            'codigoTributacaoNacionalIss' => '010701',
            'descricaoServico' => 'Serviço de teste',
            'valorServico' => 1.00,
            'tributacaoIss' => 1,
            'tipoRetencaoIss' => 1,
        ]);

        $this->assertSame('18765499000199', $dto->cnpjPrestador);
        $this->assertSame(4106902, $dto->codigoMunicipioEmissora);
    }

    public function test_to_array_converte_para_snake_case(): void
    {
        $dto = NFSeNDTO::fromArray($this->makeBaseData());
        $payload = $dto->toArray();

        $this->assertArrayHasKey('data_emissao', $payload);
        $this->assertArrayHasKey('data_competencia', $payload);
        $this->assertArrayHasKey('codigo_municipio_emissora', $payload);
        $this->assertArrayHasKey('cnpj_prestador', $payload);
        $this->assertArrayHasKey('inscricao_municipal_prestador', $payload);
        $this->assertArrayHasKey('codigo_opcao_simples_nacional', $payload);
        $this->assertArrayHasKey('regime_especial_tributacao', $payload);
        $this->assertArrayHasKey('cnpj_tomador', $payload);
        $this->assertArrayHasKey('razao_social_tomador', $payload);
        $this->assertArrayHasKey('codigo_municipio_prestacao', $payload);
        $this->assertArrayHasKey('codigo_tributacao_nacional_iss', $payload);
        $this->assertArrayHasKey('valor_servico', $payload);
        $this->assertArrayHasKey('tributacao_iss', $payload);
        $this->assertArrayHasKey('tipo_retencao_iss', $payload);
    }

    public function test_campos_opcionais_nulos_sao_incluidos_no_to_array(): void
    {
        $dto = NFSeNDTO::fromArray($this->makeBaseData());
        $payload = $dto->toArray();

        $this->assertNull($payload['cep_tomador']);
        $this->assertNull($payload['logradouro_tomador']);
        $this->assertNull($payload['email_tomador']);
    }
}
