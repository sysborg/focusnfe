# FAQ

## O pacote funciona em sandbox e producao?

Sim. O ambiente e configurado em `FOCUSNFE_AMBIENTE`.

## Preciso usar a facade?

Nao. Todos os services podem ser resolvidos diretamente pelo container do Laravel.

## O webhook usa HMAC?

Pelo manual local versionado no projeto, os campos documentados para autenticacao do webhook sao `authorization` e `authorization_header`. A estrategia recomendada hoje e validar esse token compartilhado no endpoint receptor.

## O pacote ja possui retry e rate limit?

Sim. O `FocusNfeHttp` aplica retry configuravel e rate limiting local opcional.

## As respostas ja possuem DTO dedicado?

Ainda nao de forma geral. No estado atual, a resposta principal continua sendo `Illuminate\Http\Client\Response`, com alguns DTOs tipados em consultas especificas, como CNPJ.
