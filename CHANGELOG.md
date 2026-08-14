# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project aims to follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- facade `FocusNfe` com auto-discovery do Laravel
- `FocusNfeManager` para acesso centralizado aos services do pacote
- cobertura unitaria para DTOs auxiliares, services complementares e eventos Laravel
- documentacao inicial em `docs/` para instalacao, configuracao, exemplos, eventos, FAQ e respostas
- arquivos de contribuicao e templates para issues e pull requests
- suporte NFe para cancelamento com justificativa, importacao XML, filtros de inutilizacoes e eventos genericos
- estrategia de homologacao NFe em `docs/homologacao-nfe.md`

### Changed
- README atualizado para refletir os services, eventos e guias disponiveis hoje
- roadmap e plano estrategico alinhados ao estado real do repositorio
- `NFeDTO` serializa itens como `items` e mantem aliases legados na entrada
- exemplos NFe atualizados com emissao, consulta, cancelamento, carta de correcao, inutilizacao, importacao XML, email, ECONF e hook
