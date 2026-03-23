<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\MDFe;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class MDFeServiceTest extends TestCase
{
    use BootstrapsFacadesTrait;

    private MDFe $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $ref = 'mdfe-001';

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
        $this->service = new MDFe('test-token', 'production');
    }

    public function test_envia_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL => Http::response(['status' => 'processando_autorizacao'], 202),
        ]);

        $response = $this->service->envia(['cnpj_emitente' => '11111151000119']);

        $this->assertSame(202, $response->status());
    }

    public function test_consulta_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL . '/' . $this->ref => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->consulta($this->ref);

        $this->assertSame(200, $response->status());
    }

    public function test_cancela_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL . '/' . $this->ref => Http::response(['status' => 'cancelado'], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertSame(200, $response->status());
    }

    public function test_inclui_condutor_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL . '/' . $this->ref . '/inclusao_condutor' => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->incluiCondutor($this->ref, ['nome_condutor' => 'Fulano']);

        $this->assertSame(200, $response->status());
    }

    public function test_inclui_dfe_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL . '/' . $this->ref . '/inclusao_dfe' => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->incluiDFe($this->ref, ['chave_nfe' => '35260107504505000132550010000000011234567890']);

        $this->assertSame(200, $response->status());
    }

    public function test_encerra_mdfe(): void
    {
        Http::fake([
            $this->baseUrl . MDFe::URL . '/' . $this->ref . '/encerrar' => Http::response(['status' => 'encerrado'], 200),
        ]);

        $response = $this->service->encerra($this->ref);

        $this->assertSame(200, $response->status());
        $this->assertSame('encerrado', $response->json('status'));
    }
}
