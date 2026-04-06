# Instalacao

## Requisitos

- PHP 8.0+
- Laravel 9+
- Token de acesso da FocusNFe

## Composer

```bash
composer require sysborg/focusnfe
```

## Publicando a configuracao

```bash
php artisan vendor:publish --tag=config
```

## Variaveis de ambiente

```env
FOCUSNFE_TOKEN=seu-token-focusnfe
FOCUSNFE_AMBIENTE=production
FOCUSNFE_LOG_CHANNEL=stack
FOCUSNFE_LOG_LEVEL=error
FOCUSNFE_RATE_LIMIT_ENABLED=true
FOCUSNFE_RATE_LIMIT_MAX_ATTEMPTS=60
FOCUSNFE_RATE_LIMIT_DECAY_SECONDS=60
FOCUSNFE_RETRY_TIMES=3
FOCUSNFE_RETRY_SLEEP=1000
```

## Primeiro teste rapido

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->get('minha-referencia');

if ($response->successful()) {
    $payload = $response->json();
}
```

## Proximos guias

- [Configuracao](./configuracao.md)
- [Exemplos](./exemplos.md)
- [Erros e Respostas](./erros-e-respostas.md)
- [Eventos](./eventos.md)
- [FAQ](./faq.md)
