# Erros e Respostas

## Resposta bruta atual

Hoje os services retornam `Illuminate\Http\Client\Response` diretamente. Isso permite usar os helpers nativos do Laravel sem camada extra:

```php
$response->successful();
$response->failed();
$response->status();
$response->json();
$response->throw();
```

## Eventos disparados pelos services

Quando um fluxo possui evento Laravel associado, o payload padrao disparado segue este formato:

```php
[
    'status' => 200,
    'data' => [...],
    'success' => true,
]
```

## Rate limit local

Se o limite local configurado for excedido, o pacote lanca `Sysborg\FocusNfe\app\Exceptions\RateLimitException`.

## Logs

Erros e respostas relevantes podem ser registrados via `FocusNfeLogger`, com sanitizacao automatica de dados sensiveis.
