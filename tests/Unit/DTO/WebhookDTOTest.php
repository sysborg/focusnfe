<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\WebhookDTO;

class WebhookDTOTest extends TestCase
{
    public function test_to_array_serializa_campos_de_autenticacao(): void
    {
        $dto = new WebhookDTO(
            url: 'https://meuapp.com/hooks',
            evento: 'nfe_autorizada',
            cnpjEmitente: '07504505000132',
            authorization: 'Bearer focus-token',
            authorizationHeader: 'Authorization',
        );

        $payload = $dto->toArray();

        $this->assertSame('07504505000132', $payload['cnpj_emitente']);
        $this->assertSame('https://meuapp.com/hooks', $payload['url']);
        $this->assertSame('nfe_autorizada', $payload['evento']);
        $this->assertSame('Bearer focus-token', $payload['authorization']);
        $this->assertSame('Authorization', $payload['authorization_header']);
    }

    public function test_to_array_serializa_cpf_emitente(): void
    {
        $dto = new WebhookDTO(
            url: 'https://meuapp.com/hooks',
            evento: 'nfse_autorizada',
            cpfEmitente: '12345678901',
        );

        $payload = $dto->toArray();

        $this->assertSame('12345678901', $payload['cpf_emitente']);
        $this->assertNull($payload['cnpj_emitente']);
    }

    public function test_from_array_aceita_snake_case(): void
    {
        $dto = WebhookDTO::fromArray([
            'cnpj_emitente' => '07504505000132',
            'url' => 'https://meuapp.com/hooks',
            'evento' => 'cte_autorizado',
            'authorization' => 'Bearer abc',
            'authorization_header' => 'X-Webhook-Token',
        ]);

        $this->assertSame('cte_autorizado', $dto->evento);
        $this->assertSame('Bearer abc', $dto->authorization);
        $this->assertSame('X-Webhook-Token', $dto->authorizationHeader);
    }

    public function test_from_array_aceita_event_alias(): void
    {
        $dto = WebhookDTO::fromArray([
            'url' => 'https://meuapp.com/hooks',
            'event' => 'nfe_recebida',
        ]);

        $this->assertSame('nfe_recebida', $dto->evento);
    }

    public function test_eventos_suportados_incluem_novos_tipos(): void
    {
        $this->assertContains('nfe_autorizada', WebhookDTO::EVENTOS);
        $this->assertContains('nfe_cancelada', WebhookDTO::EVENTOS);
        $this->assertContains('nfe_recebida', WebhookDTO::EVENTOS);
        $this->assertContains('nfe_recebida_falha_consulta', WebhookDTO::EVENTOS);
        $this->assertContains('nfce_contingencia', WebhookDTO::EVENTOS);
        $this->assertContains('nfse_recebida', WebhookDTO::EVENTOS);
        $this->assertContains('cte_recebida', WebhookDTO::EVENTOS);
        $this->assertContains('inutilizacao', WebhookDTO::EVENTOS);
        $this->assertContains('nfcom', WebhookDTO::EVENTOS);
    }
}
