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
            'data_emissao' => '2024-05-07T07:34:56-0300',
            'prestador' => [
                'cnpj' => '18765499000199',
                'inscricao_municipal' => '12345',
                'codigo_municipio' => '4106902',
            ],
            'tomador' => [
                'cnpj' => '07504505000132',
                'razao_social' => 'Tomador Teste',
                'email' => 'tomador@example.com',
                'endereco' => [
                    'logradouro' => 'Rua Teste',
                    'numero' => '100',
                    'bairro' => 'Centro',
                    'codigo_municipio' => '4106902',
                    'uf' => 'PR',
                    'cep' => '80000000',
                ],
            ],
            'servico' => [
                'aliquota' => 5,
                'discriminacao' => 'Servico de teste',
                'iss_retido' => false,
                'item_lista_servico' => '0107',
                'codigo_tributario_municipio' => '1234',
                'valor_servicos' => 100,
            ],
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
