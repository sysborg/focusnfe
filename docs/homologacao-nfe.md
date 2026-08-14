# Estrategia de homologacao NFe - Focus NFe v2

Data: 2026-08-14
Escopo: pacote `sysborg/focusnfe`, somente NFe.

## Decisao

Este pacote nao deve executar homologacao fiscal real sozinho.

A homologacao real depende do aplicativo consumidor, porque exige token Focus NFe, empresa emitente habilitada, certificado/configuracao fiscal, numeracao, destinatario de teste, XML de teste e controle de ambiente. Esses dados nao pertencem ao pacote e nao devem ser versionados aqui.

Portanto, a entrega de homologacao dentro do pacote e:

- contratos HTTP implementados e cobertos por testes;
- roteiro oficial para o app consumidor executar em sandbox;
- criterios de aprovacao;
- checklist de evidencias;
- lista de variaveis/dados exigidos;
- limites claros do que nao deve entrar no pacote.

## 7. Homologacao

Status: concluida como estrategia de homologacao do pacote.

O pacote esta pronto para ser homologado no aplicativo consumidor desde que o app configure:

```env
FOCUSNFE_TOKEN=token-de-homologacao
FOCUSNFE_AMBIENTE=sandbox
```

Base sandbox esperada:

```text
https://homologacao.focusnfe.com.br
```

Contrato de pacote coberto por testes:

- Emissao NFe via `NFe::envia()`.
- Consulta simples e completa via `NFe::get()`.
- Cancelamento com justificativa via `NFe::cancela()`.
- Carta de correcao via `NFe::cartaCorrecao()`.
- Inutilizacao via `NFe::inutilizar()`.
- Consulta de inutilizacoes com filtros via `NFe::inutilizacoes()`.
- Importacao XML via `NFe::importaXml()`.
- Email via `NFe::reenviaEmail()`.
- ECONF via `registraEconf()`, `consultaEconf()` e `cancelaEconf()`.
- Hook via `reenviarHook()`.
- Eventos auxiliares via `insucessoEntrega()`, `cancelaInsucessoEntrega()`, `atorInteressado()`, `evento()`, `cancelaEvento()` e `prorrogacaoIcms()`.

## 7.1 Preparar ambiente

Responsavel: aplicacao consumidora.

O app consumidor deve configurar:

- token de homologacao Focus NFe;
- `FOCUSNFE_AMBIENTE=sandbox`;
- empresa emitente habilitada para NFe em homologacao;
- certificado/configuracao fiscal exigida pela Focus NFe;
- serie e numeracao de homologacao;
- dados de destinatario de teste;
- email de teste;
- XML de importacao de teste;
- faixa de numeracao disponivel para inutilizacao;
- NFe elegivel para eventos condicionais, como carta de correcao e ECONF.

Convencoes recomendadas:

- referencias com prefixo `hom-nfe-{timestamp}`;
- nenhum dado sensivel real em fixtures;
- logs com token, CNPJ/CPF, email, certificado e senha mascarados.

## 7.2 Roteiro de homologacao

O app consumidor deve executar o fluxo abaixo no sandbox:

1. Emitir NFe com payload oficial usando `items`.
2. Confirmar `201` autorizada ou `202` processando.
3. Consultar NFe por referencia.
4. Consultar NFe com `completa=1`.
5. Enviar email para destinatario de teste.
6. Emitir carta de correcao quando o status permitir.
7. Cancelar NFe com justificativa valida.
8. Consultar NFe cancelada.
9. Inutilizar faixa de numeracao de teste.
10. Consultar inutilizacoes com filtros.
11. Importar NFe por XML com referencia nova.
12. Registrar ECONF em NFe elegivel.
13. Consultar ECONF por numero de protocolo.
14. Cancelar ECONF por numero de protocolo.
15. Reenviar hook.
16. Validar eventos auxiliares quando aplicavel:
    - insucesso de entrega;
    - cancelamento de insucesso de entrega;
    - ator interessado;
    - prorrogacao de ICMS via `evento`.

## 7.3 Evidencias

O app consumidor deve registrar:

- referencias usadas;
- endpoint chamado;
- HTTP status;
- payload sem dados sensiveis;
- `status`, `status_sefaz` e `mensagem_sefaz`, quando retornados;
- numero/protocolo de ECONF;
- resultado de cancelamento;
- resultado de inutilizacao;
- resultado de importacao XML;
- logs sem token, CNPJ/CPF real, email real, certificado ou senha.

Modelo de evidencia:

```text
Referencia: hom-nfe-YYYYMMDD-HHMMSS
Endpoint: POST /v2/nfe?ref={referencia}
HTTP status: 201|202
Status Focus: autorizado|processando_autorizacao|erro_autorizacao
Status SEFAZ: valor retornado
Mensagem SEFAZ: valor retornado
Observacao: sem dados sensiveis
```

## 7.4 Criterios de aprovacao

A homologacao da NFe e considerada aprovada quando:

- emissao funciona com `items`;
- consulta simples funciona;
- consulta completa funciona;
- cancelamento envia `justificativa`;
- importacao XML funciona;
- envio de email funciona;
- reenvio de hook funciona;
- carta de correcao funciona quando aplicavel;
- inutilizacao funciona quando aplicavel;
- ECONF funciona quando aplicavel;
- eventos auxiliares funcionam quando aplicavel;
- payload oficial nao depende de nomes legados;
- aliases legados continuam cobertos pelos testes do pacote;
- evidencias nao contem dados sensiveis.

## Fora do pacote

Nao entra neste pacote:

- token real;
- certificado digital;
- `.env` de cliente;
- dados fiscais reais;
- chamada obrigatoria a sandbox durante teste unitario;
- fixture com CNPJ/CPF real;
- rotina que force numeracao real de homologacao.

Esses itens pertencem ao projeto consumidor.
