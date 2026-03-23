<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\WebhookDTO;

class WebhookDTOTest extends TestCase
{
    public function test_to_array_serializa_campos_de_autenticacao(): void
    {
        $dto = new WebhookDTO(
            cnpj_emitente: '07504505000132',
            url: 'https://meuapp.com/hooks',
            evento: 'nfe_autorizada',
            authorization: 'Bearer focus-token',
            authorization_header: 'Authorization',
        );

        $payload = $dto->toArray();

        $this->assertSame('07504505000132', $payload['cnpj_emitente']);
        $this->assertSame('https://meuapp.com/hooks', $payload['url']);
        $this->assertSame('nfe_autorizada', $payload['evento']);
        $this->assertSame('Bearer focus-token', $payload['authorization']);
        $this->assertSame('Authorization', $payload['authorization_header']);
    }

    public function test_from_array_mantem_campos_opcionais(): void
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
        $this->assertSame('X-Webhook-Token', $dto->authorization_header);
    }
}
