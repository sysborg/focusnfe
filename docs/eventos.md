# Eventos Disponiveis

## Estrutura padrao

Os eventos de service carregam um array `data` com:

```php
[
    'status' => 200,
    'data' => [...],
    'success' => true,
]
```

## Eventos de documentos fiscais

- `NFeAutorizada`
- `NFeCancelada`
- `NFeInutilizada`
- `NFCeAutorizada`
- `NFCeCancelada`
- `CTeAutorizado`
- `CTeCancelado`
- `MDFeAutorizado`
- `MDFeCancelado`
- `MDFeEncerrado`
- `NFSeEnviada`
- `NFSeCancelada`
- `NFSeNacionalAutorizada`
- `NFSeNacionalCancelada`

## Eventos de empresas

- `EmpresaCreated`
- `EmpresaUpdated`
- `EmpresaDeleted`

## Evento de entrada de webhook

- `HooksReceived`

## Helper recomendado para webhook

O package não recebe webhook por HTTP, mas fornece o helper `WebhookPayloadNormalizer` para padronizar o payload antes de disparar `HooksReceived`.

## Observacao

Nem todo endpoint do pacote dispara evento. Os eventos listados acima refletem apenas o que esta implementado hoje no codigo.
