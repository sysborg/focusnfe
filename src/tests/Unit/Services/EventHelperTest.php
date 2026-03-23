<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Events\NFeAutorizada;
use Sysborg\FocusNfe\app\Services\EventHelper;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class EventHelperTest extends TestCase
{
    use BootstrapsFacadesTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    public function test_dispatch_envia_evento_com_payload_padrao(): void
    {
        $captured = null;
        app('events')->listen(NFeAutorizada::class, function (NFeAutorizada $event) use (&$captured): void {
            $captured = $event;
        });

        $helper = new class () extends EventHelper {
        };

        $response = new Response(new Psr7Response(
            202,
            ['Content-Type' => 'application/json'],
            json_encode(['status' => 'processando_autorizacao', 'ref' => 'nfe-001'])
        ));

        $helper->dispatch(NFeAutorizada::class, $response);

        $this->assertInstanceOf(NFeAutorizada::class, $captured);
        $this->assertSame(202, $captured->data['status']);
        $this->assertSame('processando_autorizacao', $captured->data['data']['status']);
        $this->assertTrue($captured->data['success']);
    }
}
