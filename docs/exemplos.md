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

## NFe

### Emissao

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
    'items' => [[
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

### Consulta simples

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->get('pedido-123');
```

### Consulta completa

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->get('pedido-123', completa: true);
```

### Cancelamento com justificativa

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->cancela('pedido-123', 'Cancelamento solicitado pelo cliente.');
```

Tambem e aceito o payload em array:

```php
$response = $nfe->cancela('pedido-123', [
    'justificativa' => 'Cancelamento solicitado pelo cliente.',
]);
```

### Carta de correcao

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->cartaCorrecao('pedido-123', [
    'correcao' => 'Correcao do endereco do destinatario.',
    'data_evento' => '2026-01-15T10:00:00-03:00',
]);
```

### Inutilizacao

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->inutilizar([
    'cnpj' => '07504505000132',
    'serie' => '1',
    'numero_inicial' => '10',
    'numero_final' => '12',
    'justificativa' => 'Quebra de sequencia em homologacao.',
]);
```

### Consulta de inutilizacoes

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->inutilizacoes([
    'cnpj' => '07504505000132',
    'data_recebimento_inicial' => '2026-01-01',
    'data_recebimento_final' => '2026-01-31',
    'numero_inicial' => 10,
    'numero_final' => 12,
]);
```

### Importacao XML

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$xml = file_get_contents(storage_path('app/nfe-homologacao.xml'));

$response = $nfe->importaXml($xml, 'pedido-xml-123');
```

### Email

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->reenviaEmail('pedido-123', [
    'cliente@example.com',
    'financeiro@example.com',
]);
```

### ECONF

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);

$response = $nfe->registraEconf('pedido-123', [
    'detalhes_pagamento' => [
        [
            'forma_pagamento' => '01',
            'valor' => 100.0,
        ],
    ],
]);

$numeroProtocolo = $response->json('protocolo');

$consulta = $nfe->consultaEconf('pedido-123', $numeroProtocolo);
$cancelamento = $nfe->cancelaEconf('pedido-123', $numeroProtocolo);
```

### Eventos auxiliares

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);

$nfe->insucessoEntrega('pedido-123', [
    'data_tentativa_entrega' => '2026-01-15T10:30:56-03:00',
    'numero_tentativas' => 1,
    'motivo_insucesso' => 4,
    'justificativa_insucesso' => 'Endereco de entrega nao localizado.',
    'latitude_entrega' => '-25.428400',
    'longitude_entrega' => '-49.273300',
    'hash_tentativa_entrega' => 'hash-gerado-pelo-aplicativo',
    'data_hash_tentativa' => '2026-01-15T10:35:00-03:00',
]);

$nfe->atorInteressado('pedido-123', [
    'cnpj' => '07504505000132',
    'permite_autorizacao_terceiros' => true,
]);

$nfe->prorrogacaoIcms('pedido-123', [
    'itens_prorrogacao_suspensao_icms' => [
        ['numero_item' => 1],
    ],
]);
```

### Hook

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->reenviarHook('pedido-123');
```

### Nomes oficiais e aliases aceitos

O payload serializado para a Focus NFe usa os nomes oficiais principais:

- `items`
- `valor_produtos`
- `valor_total`
- `notas_referenciadas`

Para compatibilidade com aplicacoes existentes, `NFeDTO::fromArray()` tambem aceita:

- `itens`
- `valor_total_produtos`
- `valor_total_nota`
- `documentos_referenciados`

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
