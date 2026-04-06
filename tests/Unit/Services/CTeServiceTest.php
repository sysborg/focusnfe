<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\CTe;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class CTeServiceTest extends TestCase
{
    use BootstrapsFacadesTrait;

    private CTe $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $ref = 'cte-001';

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
        $this->service = new CTe('test-token', 'production');
    }

    public function test_envia_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '*' => Http::response(['status' => 'processando_autorizacao'], 202),
        ]);

        $response = $this->service->envia(['cfop' => '5353'], $this->ref);

        $this->assertSame(202, $response->status());
    }

    public function test_consulta_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->consulta($this->ref);

        $this->assertSame(200, $response->status());
    }

    public function test_cancela_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref => Http::response(['status' => 'cancelado'], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertSame(200, $response->status());
    }

    public function test_carta_correcao_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref . '/carta_correcao' => Http::response(['status' => 'autorizado'], 200),
        ]);

        $response = $this->service->cartaCorrecao($this->ref, ['correcao' => 'Teste']);

        $this->assertSame(200, $response->status());
    }

    public function test_desacordo_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref . '/desacordo' => Http::response(['status' => 'registrado'], 200),
        ]);

        $response = $this->service->desacordo($this->ref, ['observacao' => 'Servico em desacordo']);

        $this->assertSame(200, $response->status());
        $this->assertSame('registrado', $response->json('status'));
    }

    public function test_registro_multimodal_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref . '/registro_multimodal' => Http::response(['status' => 'registrado'], 200),
        ]);

        $response = $this->service->registroMultimodal($this->ref, ['numero_ciot' => '123']);

        $this->assertSame(200, $response->status());
    }

    public function test_dados_gtv_cte(): void
    {
        Http::fake([
            $this->baseUrl . CTe::URL . '/' . $this->ref . '/dados_gtv' => Http::response(['status' => 'registrado'], 200),
        ]);

        $response = $this->service->dadosGtv($this->ref, ['numero_guia' => 'GTV-1']);

        $this->assertSame(200, $response->status());
    }
}
