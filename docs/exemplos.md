# Exemplos de Uso

## Facade principal

```php
use Sysborg\FocusNfe\app\Facades\FocusNfe;

$response = FocusNfe::nfe()->get('pedido-123');
```

## Service via container

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->get('pedido-123');
```

## Enviando NF-e

```php
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$dto = NFeDTO::fromArray([
    'natureza_operacao' => 'Venda de mercadoria',
    'data_emissao' => '2026-01-15T10:00:00-03:00',
    'tipo_documento' => 1,
    'local_destino' => 1,
    'finalidade_emissao' => 1,
    'consumidor_final' => 1,
    'presenca_comprador' => 1,
    'cnpj_emitente' => '07504505000132',
    'inscricao_estadual_emitente' => '111111111111',
    'logradouro_emitente' => 'Rua Teste',
    'numero_emitente' => '100',
    'bairro_emitente' => 'Centro',
    'municipio_emitente' => 'Sao Paulo',
    'uf_emitente' => 'SP',
    'regime_tributario_emitente' => 1,
    'nome_destinatario' => 'Cliente Teste',
    'cpf_destinatario' => '12345678909',
    'logradouro_destinatario' => 'Rua Cliente',
    'numero_destinatario' => '200',
    'bairro_destinatario' => 'Centro',
    'municipio_destinatario' => 'Sao Paulo',
    'uf_destinatario' => 'SP',
    'indicador_inscricao_estadual_destinatario' => 9,
    'itens' => [[
        'numero_item' => 1,
        'codigo_produto' => 'P001',
        'descricao' => 'Produto',
        'codigo_ncm' => '84713012',
        'cfop' => '5102',
        'unidade_comercial' => 'UN',
        'quantidade_comercial' => 1,
        'valor_unitario_comercial' => 100.0,
        'valor_total_bruto' => 100.0,
        'icms_situacao_tributaria' => '400',
        'icms_origem' => 0,
        'pis_situacao_tributaria' => '07',
        'cofins_situacao_tributaria' => '07',
    ]],
    'formas_pagamento' => [
        ['forma_pagamento' => '01', 'valor_pagamento' => 100.0],
    ],
]);

$response = $nfe->envia($dto, 'pedido-123');
```

## Consultando CNPJ tipado

```php
use Sysborg\FocusNfe\app\Services\Cnpjs;

$cnpjs = app(Cnpjs::class);
$empresa = $cnpjs->getDto('07504505000132');
```

## Webhooks

```php
use Sysborg\FocusNfe\app\DTO\WebhookDTO;
use Sysborg\FocusNfe\app\Services\Webhooks;

$webhooks = app(Webhooks::class);

$response = $webhooks->cadastrar(new WebhookDTO(
    cnpj_emitente: '07504505000132',
    url: 'https://seu-dominio.com/focusnfe/webhooks',
    evento: 'nfe_autorizada',
    authorization: 'Bearer token-interno',
    authorization_header: 'Authorization',
));
```

## Normalizando payload de webhook na aplicação

```php
use Sysborg\FocusNfe\app\Services\WebhookPayloadNormalizer;

WebhookPayloadNormalizer::dispatch([
    'event' => 'nfe_autorizada',
    'cnpjEmitente' => '07504505000132',
    'ref' => 'pedido-123',
], 'focusnfe:webhook');
```
