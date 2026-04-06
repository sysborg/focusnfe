# Configuracao

## Ambiente

O pacote aceita os ambientes `production` e `sandbox` pela chave `focusnfe.ambiente`.

```env
FOCUSNFE_AMBIENTE=sandbox
```

As URLs base ficam em `config/focusnfe.php`:

- `https://api.focusnfe.com.br`
- `https://homologacao.focusnfe.com.br`

## Token

O token e lido de `FOCUSNFE_TOKEN` e enviado em `Authorization: Basic {base64(token)}`.

## Retry e exponential backoff

```env
FOCUSNFE_RETRY_TIMES=3
FOCUSNFE_RETRY_SLEEP=1000
```

O sleep configurado e a base do backoff exponencial usado pelo `FocusNfeHttp`.

## Rate limit local

```env
FOCUSNFE_RATE_LIMIT_ENABLED=true
FOCUSNFE_RATE_LIMIT_MAX_ATTEMPTS=60
FOCUSNFE_RATE_LIMIT_DECAY_SECONDS=60
```

Esse controle protege sua aplicacao antes mesmo da API remota negar chamadas em excesso.

## Logs

```env
FOCUSNFE_LOG_CHANNEL=stack
FOCUSNFE_LOG_LEVEL=warning
```

O `FocusNfeLogger` suporta `debug`, `info`, `warning` e `error`, com mascaramento automatico de dados sensiveis como token, authorization, cpf e cnpj.

## Listeners de webhook

Voce pode registrar listeners para o evento `HooksReceived` usando a configuracao:

```php
'listeners' => [
    'hooks' => [
        App\Listeners\ProcessaWebhookFocusNfe::class,
    ],
],
```
