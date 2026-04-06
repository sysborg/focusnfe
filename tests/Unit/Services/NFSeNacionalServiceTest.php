<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;
use Sysborg\FocusNfe\app\Services\NFSeNacional;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class NFSeNacionalServiceTest extends TestCase
{
    use BootstrapsFacadesTrait;

    private NFSeNacional $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $ref = 'nfsen-001';

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
        $this->service = new NFSeNacional('test-token', 'production');
    }

    private function makeDto(): NFSeNDTO
    {
        return NFSeNDTO::fromArray([
            'data_emissao'                  => '2024-05-07T07:34:56-0300',
            'data_competencia'              => '2024-05',
            'codigo_municipio_emissora'     => 4106902,
            'cnpj_prestador'               => '18765499000199',
            'inscricao_municipal_prestador' => '12345',
            'codigo_opcao_simples_nacional' => 1,
            'regime_especial_tributacao'    => 0,
            'cnpj_tomador'                 => '07504505000132',
            'razao_social_tomador'         => 'Tomador Teste',
            'codigo_municipio_tomador'     => 4106902,
            'codigo_municipio_prestacao'   => 4106902,
            'codigo_tributacao_nacional_iss' => '0107',
            'descricao_servico'            => 'Servico de teste',
            'valor_servico'                => 100.0,
            'tributacao_iss'               => 5,
            'tipo_retencao_iss'            => 0,
        ]);
    }

    public function test_envia_nfse_nacional(): void
    {
        Http::fake([
            $this->baseUrl . NFSeNacional::URL => Http::response(['status' => 'processando_autorizacao'], 202),
        ]);

        $response = $this->service->envia($this->makeDto());

        $this->assertSame(202, $response->status());
    }

    public function test_consulta_nfse_nacional(): void
    {
        Http::fake([
            $this->baseUrl . NFSeNacional::URL . '/' . $this->ref => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->consulta($this->ref);

        $this->assertSame(200, $response->status());
    }

    public function test_cancela_nfse_nacional(): void
    {
        Http::fake([
            $this->baseUrl . NFSeNacional::URL . '/' . $this->ref => Http::response(['status' => 'cancelado'], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertSame(200, $response->status());
    }
}
