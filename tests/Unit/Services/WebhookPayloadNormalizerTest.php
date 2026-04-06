<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Sysborg\FocusNfe\app\Services\WebhookPayloadNormalizer;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class WebhookPayloadNormalizerTest extends TestCase
{
    use BootstrapsFacadesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    public function test_normalize_preserva_raw_e_campos_principais(): void
    {
        $payload = [
            'event' => 'nfe_autorizada',
            'cnpjEmitente' => '07504505000132',
            'ref' => 'pedido-123',
            'chaveNfe' => '35123456789012345678901234567890123456789012',
        ];

        $normalized = WebhookPayloadNormalizer::normalize($payload);

        $this->assertSame('nfe_autorizada', $normalized['evento']);
        $this->assertSame('07504505000132', $normalized['cnpj_emitente']);
        $this->assertSame('pedido-123', $normalized['referencia']);
        $this->assertSame('35123456789012345678901234567890123456789012', $normalized['chave']);
        $this->assertSame($payload, $normalized['raw']);
    }

    public function test_dispatch_dispara_hooks_received_com_payload_normalizado(): void
    {
        $captured = null;

        app('events')->listen(HooksReceived::class, function (HooksReceived $event) use (&$captured): void {
            $captured = $event;
        });

        WebhookPayloadNormalizer::dispatch([
            'tipo_evento' => 'nfse_cancelada',
            'cnpj_emitente' => '07504505000132',
            'referencia' => 'nfse-001',
        ], 'focusnfe:test');

        $this->assertInstanceOf(HooksReceived::class, $captured);
        $this->assertSame('nfse_cancelada', $captured->data['evento']);
        $this->assertSame('07504505000132', $captured->data['cnpj_emitente']);
        $this->assertSame('nfse-001', $captured->data['referencia']);
        $this->assertSame('focusnfe:test', $captured->url_referrer);
    }
}
