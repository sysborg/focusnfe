<?php

namespace Sysborg\FocusNfe\tests\Unit\Events;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Events\CTeAutorizado;
use Sysborg\FocusNfe\app\Events\CTeCancelado;
use Sysborg\FocusNfe\app\Events\EmpresaCreated;
use Sysborg\FocusNfe\app\Events\EmpresaDeleted;
use Sysborg\FocusNfe\app\Events\EmpresaUpdated;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Sysborg\FocusNfe\app\Events\MDFeAutorizado;
use Sysborg\FocusNfe\app\Events\MDFeCancelado;
use Sysborg\FocusNfe\app\Events\MDFeEncerrado;
use Sysborg\FocusNfe\app\Events\NFCeAutorizada;
use Sysborg\FocusNfe\app\Events\NFCeCancelada;
use Sysborg\FocusNfe\app\Events\NFeAutorizada;
use Sysborg\FocusNfe\app\Events\NFeCancelada;
use Sysborg\FocusNfe\app\Events\NFeInutilizada;
use Sysborg\FocusNfe\app\Events\NFSeCancelada;
use Sysborg\FocusNfe\app\Events\NFSeEnviada;
use Sysborg\FocusNfe\app\Events\NFSeNacionalAutorizada;
use Sysborg\FocusNfe\app\Events\NFSeNacionalCancelada;

class EventsTest extends TestCase
{
    private array $payload = ['status' => 'autorizado', 'referencia' => 'REF001'];

    public function test_cte_autorizado_armazena_data(): void
    {
        $event = new CTeAutorizado($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_cte_cancelado_armazena_data(): void
    {
        $event = new CTeCancelado($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_empresa_created_armazena_data(): void
    {
        $event = new EmpresaCreated($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_empresa_deleted_armazena_data(): void
    {
        $event = new EmpresaDeleted($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_empresa_updated_armazena_data(): void
    {
        $event = new EmpresaUpdated($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_hooks_received_armazena_data_e_url_referrer(): void
    {
        $url = 'https://meusite.com.br/webhook';
        $event = new HooksReceived($this->payload, $url);

        $this->assertSame($this->payload, $event->data);
        $this->assertSame($url, $event->url_referrer);
    }

    public function test_mdfe_autorizado_armazena_data(): void
    {
        $event = new MDFeAutorizado($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_mdfe_cancelado_armazena_data(): void
    {
        $event = new MDFeCancelado($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_mdfe_encerrado_armazena_data(): void
    {
        $event = new MDFeEncerrado($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfce_autorizada_armazena_data(): void
    {
        $event = new NFCeAutorizada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfce_cancelada_armazena_data(): void
    {
        $event = new NFCeCancelada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfe_autorizada_armazena_data(): void
    {
        $event = new NFeAutorizada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfe_cancelada_armazena_data(): void
    {
        $event = new NFeCancelada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfe_inutilizada_armazena_data(): void
    {
        $event = new NFeInutilizada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfse_cancelada_armazena_data(): void
    {
        $event = new NFSeCancelada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfse_enviada_armazena_data(): void
    {
        $event = new NFSeEnviada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfse_nacional_autorizada_armazena_data(): void
    {
        $event = new NFSeNacionalAutorizada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }

    public function test_nfse_nacional_cancelada_armazena_data(): void
    {
        $event = new NFSeNacionalCancelada($this->payload);
        $this->assertSame($this->payload, $event->data);
    }
}
