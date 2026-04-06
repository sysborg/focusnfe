# Guia de Migracao

## Quando este guia se aplica

Este guia ajuda quem ja usa os services diretamente e quer adotar a facade `FocusNfe` ou o `FocusNfeManager` sem mudar o comportamento do pacote.

## Antes

```php
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);
$response = $nfe->get('pedido-123');
```

## Depois com facade

```php
use Sysborg\FocusNfe\app\Facades\FocusNfe;

$response = FocusNfe::nfe()->get('pedido-123');
```

## Depois com manager

```php
use Sysborg\FocusNfe\app\Services\FocusNfeManager;

$focusnfe = app(FocusNfeManager::class);
$response = $focusnfe->nfe()->get('pedido-123');
```

## O que nao muda

- os services continuam retornando `Illuminate\Http\Client\Response`
- a configuracao continua centralizada em `config/focusnfe.php`
- os eventos Laravel seguem sendo disparados pelos mesmos fluxos implementados

## Recomendacao

Se seu projeto ja injeta services diretamente, nao ha obrigacao de migrar. A facade e o manager existem para melhorar discoverability e ergonomia no uso diario.
