# Sysborg FocusNFe

[![CI](https://github.com/sysborg/focusnfe/actions/workflows/ci.yml/badge.svg)](https://github.com/sysborg/focusnfe/actions/workflows/ci.yml)

## Pacote de implementação da FocusNFe para Laravel por Sysborg

## Introdução

O projeto tem como objetivo facilitar a integração de aplicações Laravel com a FocusNFe, utilizando uma abordagem consistente e bem estruturada.
Ele permite que o usuário escolha entre utilizar Services ou acessar os recursos diretamente por meio de rotas.

O pacote expõe services para os módulos documentados pela FocusNFe e, nos fluxos já implementados, dispara eventos Laravel para facilitar integrações internas.

## Swagger

Todas as rotas estão bem documentadas seguindo o padrão do Swagger, permitindo que você disponibilize uma documentação clara e acessível. Caso queira uma solução rápida e 
prática, basta implementar o Swagger no seu projeto Laravel para oferecer uma referência detalhada da API.
[Swagger](https://github.com/DarkaOnLine/L5-Swagger)

## Eventos

- status: É o status code do http [Saiba mais](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status)
- data: É o retorno da FocusNFe sem nenhum tipo de alteração.
- success: Se a ação esperada foi realizada efetivamente ou não.

### Evento quando a rota de webhook é estimulada
```
use \Sysborg\FocusNfe\app\Events\HooksReceived
```

O retorno é transparente e idêntico ao que a FocusNFe fornece.
Neste caso, a explicação introdutória sobre eventos não se aplica, pois trata-se de um Webhook, um tipo diferente de evento.

[Webhook - FocusNFe](https://focusnfe.com.br/doc/#gatilhos-webhooks_eventos)

### Recebimento de webhooks no Laravel

O package já oferece o client HTTP para cadastro e gestão de webhooks e também o evento `HooksReceived` para você integrar o payload recebido à sua aplicação.

Pelo manual da FocusNFe, os campos relevantes para autenticação do webhook são `authorization` e `authorization_header`. Em vez de depender de uma assinatura HMAC não documentada, a estratégia recomendada é configurar um token compartilhado no cadastro do webhook e validá-lo no seu endpoint de recepção.

Exemplo de cadastro do webhook:

```php
use Sysborg\FocusNfe\app\DTO\WebhookDTO;
use Sysborg\FocusNfe\app\Services\Webhooks;

$webhooks = app(Webhooks::class);

$webhooks->cadastrar(new WebhookDTO(
    cnpj_emitente: '07504505000132',
    url: 'https://seu-dominio.com/focusnfe/webhooks',
    evento: 'nfe_autorizada',
    authorization: 'Bearer meu-token-interno',
    authorization_header: 'Authorization',
));
```

Exemplo simples de rota/controller para receber o webhook:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Sysborg\FocusNfe\app\Events\HooksReceived;

Route::post('/focusnfe/webhooks', function (Request $request) {
    $expectedHeader = 'Authorization';
    $expectedValue = 'Bearer meu-token-interno';

    if ($request->header($expectedHeader) !== $expectedValue) {
        abort(Response::HTTP_UNAUTHORIZED, 'Webhook nao autorizado.');
    }

    Event::dispatch(new HooksReceived(
        $request->all(),
        (string) $request->headers->get('referer', '')
    ));

    return response()->json(['ok' => true]);
});
```

Se quiser trocar o nome do cabeçalho, configure o campo `authorization_header` no `WebhookDTO` e valide o mesmo nome no seu endpoint.

### Padrão de dados para os eventos abaixo

Todos os eventos abaixo retornam a mesma estrutura de dados.

```
[
    'status' => int,
    'data' => array, // retorno transparente da FocusNFe
    'success' => bool
]
```

### Cobertura atual de eventos

Eventos atualmente disponíveis no package:

- `EmpresaCreated`
- `EmpresaUpdated`
- `EmpresaDeleted`
- `HooksReceived`
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

Observação importante:

- os eventos acima refletem a cobertura atual do código
- nem todo endpoint existente no pacote já dispara evento
- `HooksReceived` é um evento de entrada de webhook, diferente dos eventos de retorno de service

### Eventos por módulo

#### Empresa

#### Criação da empresa
```
use \Sysborg\FocusNfe\app\Events\EmpresaCreated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Criação de empresa](https://focusnfe.com.br/doc/#empresas_criacao-de-empresa)

#### Alteração da empresa
```
use \Sysborg\FocusNfe\app\Events\EmpresaUpdated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Alteração de empresa](https://focusnfe.com.br/doc/#empresas_alteracao-de-empresa)

#### Exclusão da empresa
```
use \Sysborg\FocusNfe\app\Events\EmpresaCreated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Exclusão de empresa](https://focusnfe.com.br/doc/#empresas_exclusao-de-empresa)

#### NFe
```
use \Sysborg\FocusNfe\app\Events\NFeAutorizada;
use \Sysborg\FocusNfe\app\Events\NFeCancelada;
use \Sysborg\FocusNfe\app\Events\NFeInutilizada;
```

#### NFCe
```
use \Sysborg\FocusNfe\app\Events\NFCeAutorizada;
use \Sysborg\FocusNfe\app\Events\NFCeCancelada;
```

#### CTe
```
use \Sysborg\FocusNfe\app\Events\CTeAutorizado;
use \Sysborg\FocusNfe\app\Events\CTeCancelado;
```

#### MDFe
```
use \Sysborg\FocusNfe\app\Events\MDFeAutorizado;
use \Sysborg\FocusNfe\app\Events\MDFeCancelado;
use \Sysborg\FocusNfe\app\Events\MDFeEncerrado;
```

#### NFSe Municipal
```
use \Sysborg\FocusNfe\app\Events\NFSeEnviada
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[NFSe - Envio](https://focusnfe.com.br/doc/#nfse_envio)

#### NFSe - Cancelada
```
use \Sysborg\FocusNfe\app\Events\NFSeCancelada
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[NFSe - Cancelamento](https://focusnfe.com.br/doc/#nfse_cancelamento)

#### NFSe Nacional
```
use \Sysborg\FocusNfe\app\Events\NFSeNacionalAutorizada;
use \Sysborg\FocusNfe\app\Events\NFSeNacionalCancelada;
```

#### Documentação Oficial do Laravel
[Documentação Laravel de Eventos](https://laravel.com/docs/12.x/events)

## Rotas

## Controllers

## Services

Os services atualmente disponíveis no pacote são:

- `Backups`
- `CEP`
- `CFOP`
- `CNAE`
- `Cnpjs`
- `ConsultaEmails`
- `CTe`
- `CTERecebidas`
- `Empresas`
- `MDFe`
- `Municipios`
- `NCM`
- `NFCe`
- `NFe`
- `NFeRecebidas`
- `NFSe`
- `NFSeArquivo`
- `NFSeNacional`
- `NFSeRecebidas`
- `Webhooks`

### Exemplo básico de resolução via container

```php
use Sysborg\FocusNfe\app\Services\NFe;

$service = app(NFe::class);
```

### NFe

Operações atualmente suportadas:

- `envia`
- `get`
- `cancela`
- `cartaCorrecao`
- `inutilizar`
- `inutilizacoes`
- `reenviaEmail`
- `downloadXml`
- `insucessoEntrega`
- `atorInteressado`
- `prorrogacaoIcms`
- `registraEconf`
- `consultaEconf`
- `cancelaEconf`

Exemplo:

```php
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\Services\NFe;

$nfe = app(NFe::class);

$response = $nfe->get('minha-referencia');
```

### NFCe

Operações atualmente suportadas:

- `envia`
- `get`
- `cancela`
- `inutilizacoes`
- `reenviaEmail`
- `registraEconf`
- `consultaEconf`
- `cancelaEconf`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\NFCe;

$nfce = app(NFCe::class);
$response = $nfce->get('minha-referencia');
```

### CTe

Operações atualmente suportadas:

- `envia`
- `consulta`
- `cancela`
- `cartaCorrecao`
- `desacordo`
- `registroMultimodal`
- `dadosGtv`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\CTe;

$cte = app(CTe::class);
$response = $cte->consulta('minha-referencia');
```

### MDFe

Operações atualmente suportadas:

- `envia`
- `consulta`
- `cancela`
- `incluiCondutor`
- `incluiDFe`
- `encerra`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\MDFe;

$mdfe = app(MDFe::class);
$response = $mdfe->consulta('minha-referencia');
```

### NFSe Municipal

Operações atualmente suportadas:

- `envia`
- `get`
- `cancela`
- `reenviaEmail`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\NFSe;

$nfse = app(NFSe::class);
$response = $nfse->get('minha-referencia');
```

### NFSe Nacional

Operações atualmente suportadas:

- `envia`
- `consulta`
- `cancela`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\NFSeNacional;

$nfsen = app(NFSeNacional::class);
$response = $nfsen->consulta('minha-referencia');
```

### Webhooks

Operações atualmente suportadas:

- `cadastrar`
- `listar`
- `consultar`
- `atualizar`
- `remover`
- `testar`

Exemplo:

```php
use Sysborg\FocusNfe\app\Services\Webhooks;

$webhooks = app(Webhooks::class);
$response = $webhooks->listar();
```

## Testes automáticos

O repositório já possui cobertura para DTOs e services principais, incluindo cenários adicionados recentemente para:

- `NFe`
- `NFCe`
- `CTe`
- `MDFe`
- `NFSeNacional`
- `Webhooks`
- `EmpresaDTO`
- `Cnpjs` com `getDto()`

## DTOs utilitários recentes

### CNPJ tipado

Além da `Response` bruta do service `Cnpjs`, o pacote agora expõe conversão tipada para os campos documentados no manual local.

```php
use Sysborg\FocusNfe\app\Services\Cnpjs;

$cnpjs = app(Cnpjs::class);
$empresa = $cnpjs->getDto('07504505000132');

if ($empresa) {
    $razaoSocial = $empresa->razao_social;
    $uf = $empresa->endereco?->uf;
}
```

### EmpresaDTO

O `EmpresaDTO` cobre os campos atualmente documentados no manual local para:

- habilitação de `NFSe Nacional`
- `CSC` e `ID Token` de produção e homologação
- séries e próximas numerações de `NFe`, `NFCe`, `NFSe`, `NFSe Nacional`, `CTe`, `CTe OS` e `MDFe`
- flags operacionais como manifestação, contingência offline e emissão síncrona

## Instalação

## Documentação da FocusNFe

[Documentação](https://focusnfe.com.br/doc/#introducao)
