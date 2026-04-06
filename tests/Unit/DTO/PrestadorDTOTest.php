<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class PrestadorDTOTest extends TestCase
{
    use BootstrapsFacadesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    public function test_cria_prestador_dto_com_dados_validos(): void
    {
        $prestador = new PrestadorDTO(
            cnpj: '07504505000132',
            inscricaoMunicipal: '12345',
            codigoMunicipio: '3516200'
        );

        $this->assertEquals('07504505000132', $prestador->cnpj);
        $this->assertEquals('12345', $prestador->inscricaoMunicipal);
        $this->assertEquals('3516200', $prestador->codigoMunicipio);
    }

    public function test_cria_prestador_dto_from_array(): void
    {
        $data = [
            'cnpj' => '07504505000132',
            'inscricao_municipal' => '12345',
            'codigo_municipio' => '3516200',
        ];

        $prestador = PrestadorDTO::fromArray($data);

        $this->assertInstanceOf(PrestadorDTO::class, $prestador);
        $this->assertEquals('07504505000132', $prestador->cnpj);
        $this->assertEquals('12345', $prestador->inscricaoMunicipal);
    }

    public function test_valida_cnpj_vazio(): void
    {
        $this->expectException(ValidationException::class);

        new PrestadorDTO(
            cnpj: '',
            inscricaoMunicipal: '12345',
            codigoMunicipio: '3516200'
        );
    }

    public function test_valida_inscricao_municipal_vazia(): void
    {
        $this->expectException(ValidationException::class);

        new PrestadorDTO(
            cnpj: '07504505000132',
            inscricaoMunicipal: '',
            codigoMunicipio: '3516200'
        );
    }

    public function test_valida_codigo_municipio_vazio(): void
    {
        $this->expectException(ValidationException::class);

        new PrestadorDTO(
            cnpj: '07504505000132',
            inscricaoMunicipal: '12345',
            codigoMunicipio: ''
        );
    }

    public function test_to_array(): void
    {
        $prestador = new PrestadorDTO(
            cnpj: '07504505000132',
            inscricaoMunicipal: '12345',
            codigoMunicipio: '3516200'
        );

        $array = $prestador->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('inscricao_municipal', $array);
        $this->assertArrayHasKey('codigo_municipio', $array);
        $this->assertEquals('07504505000132', $array['cnpj']);
    }
}
