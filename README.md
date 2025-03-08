# Sysborg FocusNFe

## Pacote de implementação da FocusNFe para Laravel por Sysborg

## Introdução

O projeto tem como objetivo facilitar a integração de aplicações Laravel com a FocusNFe, utilizando uma abordagem consistente e bem estruturada.
Ele permite que o usuário escolha entre utilizar Services ou acessar os recursos diretamente por meio de rotas.

Além disso, todos os recursos contam com Eventos, proporcionando flexibilidade para integrar os dados ao banco de dados da sua empresa da maneira que preferir,
sem impor regras sobre como armazená-los ou manipulá-los. Dessa forma, o pacote se adapta às suas necessidades, garantindo liberdade e controle total sobre a implementação.

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
use \Sysborg\FocusNFe\app\Events\HooksReceived
```

O retorno é transparente e idêntico ao que a FocusNFe fornece.
Neste caso, a explicação introdutória sobre eventos não se aplica, pois trata-se de um Webhook, um tipo diferente de evento.

[Webhook - FocusNFe](https://focusnfe.com.br/doc/#gatilhos-webhooks_eventos)

### Padrão de dados para os eventos abaixo

Todos os eventos abaixo retornam a mesma estrutura de dados.

```
[
    'status' => int,
    'data' => array, // retorno transparente da FocusNFe
    'success' => bool
]
```

### Empresa

#### Criação da empresa
```
use \Sysborg\FocusNFe\app\Events\EmpresaCreated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Criação de empresa](https://focusnfe.com.br/doc/#empresas_criacao-de-empresa)

#### Alteração da empresa
```
use \Sysborg\FocusNFe\app\Events\EmpresaUpdated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Alteração de empresa](https://focusnfe.com.br/doc/#empresas_alteracao-de-empresa)

#### Exclusão da empresa
```
use \Sysborg\FocusNFe\app\Events\EmpresaCreated
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[Exclusão de empresa](https://focusnfe.com.br/doc/#empresas_exclusao-de-empresa)

#### NFSe - Enviada
```
use \Sysborg\FocusNFe\app\Events\NFSeEnviada
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[NFSe - Envio](https://focusnfe.com.br/doc/#nfse_envio)

#### NFSe - Cancelada
```
use \Sysborg\FocusNFe\app\Events\NFSeCancelada
```

Os dados enviados pelo evento são explicados no início da sessão eventos.
[NFSe - Cancelamento](https://focusnfe.com.br/doc/#nfse_cancelamento)

#### Documentação Oficial do Laravel
[Documentação Laravel de Eventos](https://laravel.com/docs/12.x/events)

## Rotas

## Controllers

## Services

## Testes automáticos

## Instalação

## Documentação da FocusNFe

[Documentação](https://focusnfe.com.br/doc/#introducao)
