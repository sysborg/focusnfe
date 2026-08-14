<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;

/**
 * DTO para emissão de NF-e (Nota Fiscal Eletrônica)
 *
 * Cobre todos os campos documentados na API FocusNFe v2 para NF-e 4.0,
 * incluindo campos de formas_pagamento (obrigatório 4.0), transporte,
 * eventos especiais e campos da Reforma Tributária.
 */
class NFeDTO extends DTO
{
    public function __construct(
        // Identificação
        public string $natureza_operacao,
        public Carbon $data_emissao,
        public int $tipo_documento,                     // 0=entrada, 1=saída
        public int $local_destino,                      // 1=interna, 2=interestadual, 3=exterior
        public int $finalidade_emissao,                 // 1=normal, 2=complementar, 3=ajuste, 4=devolução
        public int $consumidor_final,                   // 0=não, 1=sim
        public int $presenca_comprador,                 // 0=não se aplica, 1=presencial, 2=internet, etc.

        // Emitente
        public string $cnpj_emitente,
        public string $cpf_emitente,
        public string $inscricao_estadual_emitente,
        public string $logradouro_emitente,
        public string $numero_emitente,
        public string $bairro_emitente,
        public string $municipio_emitente,
        public string $uf_emitente,
        public int $regime_tributario_emitente,         // 1=simples, 2=simples excesso, 3=normal

        // Destinatário
        public string $nome_destinatario,
        public ?string $cnpj_destinatario,
        public ?string $cpf_destinatario,
        public ?string $inscricao_estadual_destinatario,
        public string $logradouro_destinatario,
        public string $numero_destinatario,
        public string $bairro_destinatario,
        public string $municipio_destinatario,
        public string $uf_destinatario,
        public int $indicador_inscricao_estadual_destinatario, // 1=contribuinte, 2=isento, 9=não contribuinte

        // Itens (obrigatório)
        public array $itens,

        // Formas de pagamento (obrigatório NF-e 4.0)
        // Cada item: {forma_pagamento, valor_pagamento, ?cnpj_credenciadora, ?bandeira_operadora, ?numero_autorizacao}
        public array $formas_pagamento = [],

        // Identificação — campos opcionais
        public ?int $serie = null,
        public ?int $numero = null,
        public ?Carbon $data_entrada_saida = null,

        // Emitente — campos opcionais
        public ?string $nome_emitente = null,
        public ?string $codigo_municipio_emitente = null,
        public ?string $complemento_emitente = null,
        public ?string $cep_emitente = null,
        public ?string $telefone_emitente = null,
        public ?string $email_emitente = null,
        public ?string $inscricao_municipal_emitente = null,
        public ?string $nome_fantasia_emitente = null,

        // Destinatário — campos opcionais
        public ?string $complemento_destinatario = null,
        public ?string $cep_destinatario = null,
        public ?string $telefone_destinatario = null,
        public ?string $email_destinatario = null,
        public ?string $nome_fantasia_destinatario = null,
        public ?string $inscricao_municipal_destinatario = null,
        public ?string $codigo_municipio_destinatario = null,
        public ?string $pais_destinatario = null,

        // Frete
        public ?int $modalidade_frete = null,           // 0=emitente, 1=destinatário, 2=terceiros, 9=sem frete
        public ?array $transporte = null,               // {cnpj_transportador, nome_transportador, ie_transportador, ...}

        // Totais (calculados pela FocusNFe; informar quando necessário)
        public ?float $valor_total_produtos = null,
        public ?float $valor_frete = null,
        public ?float $valor_seguro = null,
        public ?float $valor_desconto = null,
        public ?float $valor_outras_despesas = null,
        public ?float $valor_total_ii = null,
        public ?float $valor_ipi = null,
        public ?float $valor_ipi_devolvido = null,
        public ?float $valor_pis = null,
        public ?float $valor_cofins = null,
        public ?float $valor_total_nota = null,

        // Informações adicionais
        public ?string $informacoes_adicionais_contribuinte = null,
        public ?string $informacoes_adicionais_fisco = null,

        // Documentos referenciados (devolução, complementar, ajuste)
        public ?array $documentos_referenciados = null, // [{chave_nfe}] ou [{nf_referenciada: {}}]

        // Intermediador de transação (Reforma Tributária / marketplace)
        public ?string $indicador_intermed_transacao = null, // 0=sem intermediador, 1=com intermediador
        public ?string $cnpj_intermediador = null,
        public ?string $id_cadastro_intermediador = null,

        // Campos oficiais ainda não tipados no DTO. Útil para campos fiscais específicos.
        public array $campos_extras = [],
    ) {
    }

    protected function specialCases(): array
    {
        return [
            'data_emissao' => fn (Carbon $v) => $v->utc()->toIso8601String(),
            'data_entrada_saida' => fn (?Carbon $v) => $v?->utc()->toIso8601String(),
        ];
    }

    protected static function fieldMapping(): array
    {
        return [
            'itens' => 'items',
            'valor_total_produtos' => 'valor_produtos',
            'valor_total_nota' => 'valor_total',
            'documentos_referenciados' => 'notas_referenciadas',
            'indicador_intermed_transacao' => 'indicador_intermediario',
            'cnpj_intermediador' => 'cnpj_intermediario',
            'id_cadastro_intermediador' => 'id_intermediario',
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            natureza_operacao: $data['natureza_operacao'],
            data_emissao: new Carbon($data['data_emissao']),
            tipo_documento: (int) $data['tipo_documento'],
            local_destino: (int) $data['local_destino'],
            finalidade_emissao: (int) $data['finalidade_emissao'],
            consumidor_final: (int) $data['consumidor_final'],
            presenca_comprador: (int) $data['presenca_comprador'],
            serie: isset($data['serie']) ? (int) $data['serie'] : null,
            numero: isset($data['numero']) ? (int) $data['numero'] : null,
            data_entrada_saida: isset($data['data_entrada_saida']) ? new Carbon($data['data_entrada_saida']) : null,
            cnpj_emitente: $data['cnpj_emitente'],
            cpf_emitente: $data['cpf_emitente'] ?? '',
            inscricao_estadual_emitente: $data['inscricao_estadual_emitente'],
            logradouro_emitente: $data['logradouro_emitente'],
            numero_emitente: $data['numero_emitente'],
            bairro_emitente: $data['bairro_emitente'],
            municipio_emitente: $data['municipio_emitente'],
            uf_emitente: $data['uf_emitente'],
            regime_tributario_emitente: (int) $data['regime_tributario_emitente'],
            nome_emitente: $data['nome_emitente'] ?? null,
            codigo_municipio_emitente: $data['codigo_municipio_emitente'] ?? null,
            nome_destinatario: $data['nome_destinatario'],
            cnpj_destinatario: $data['cnpj_destinatario'] ?? null,
            cpf_destinatario: $data['cpf_destinatario'] ?? null,
            inscricao_estadual_destinatario: $data['inscricao_estadual_destinatario'] ?? null,
            logradouro_destinatario: $data['logradouro_destinatario'],
            numero_destinatario: $data['numero_destinatario'],
            bairro_destinatario: $data['bairro_destinatario'],
            municipio_destinatario: $data['municipio_destinatario'],
            uf_destinatario: $data['uf_destinatario'],
            indicador_inscricao_estadual_destinatario: (int) $data['indicador_inscricao_estadual_destinatario'],
            codigo_municipio_destinatario: $data['codigo_municipio_destinatario'] ?? null,
            pais_destinatario: $data['pais_destinatario'] ?? null,
            itens: $data['items'] ?? $data['itens'],
            formas_pagamento: $data['formas_pagamento'] ?? [],
            complemento_emitente: $data['complemento_emitente'] ?? null,
            cep_emitente: $data['cep_emitente'] ?? null,
            telefone_emitente: $data['telefone_emitente'] ?? null,
            email_emitente: $data['email_emitente'] ?? null,
            inscricao_municipal_emitente: $data['inscricao_municipal_emitente'] ?? null,
            nome_fantasia_emitente: $data['nome_fantasia_emitente'] ?? null,
            complemento_destinatario: $data['complemento_destinatario'] ?? null,
            cep_destinatario: $data['cep_destinatario'] ?? null,
            telefone_destinatario: $data['telefone_destinatario'] ?? null,
            email_destinatario: $data['email_destinatario'] ?? null,
            nome_fantasia_destinatario: $data['nome_fantasia_destinatario'] ?? null,
            inscricao_municipal_destinatario: $data['inscricao_municipal_destinatario'] ?? null,
            modalidade_frete: isset($data['modalidade_frete']) ? (int) $data['modalidade_frete'] : null,
            transporte: $data['transporte'] ?? null,
            valor_total_produtos: isset($data['valor_total_produtos']) || isset($data['valor_produtos'])
                ? (float) ($data['valor_total_produtos'] ?? $data['valor_produtos'])
                : null,
            valor_frete: isset($data['valor_frete']) ? (float) $data['valor_frete'] : null,
            valor_seguro: isset($data['valor_seguro']) ? (float) $data['valor_seguro'] : null,
            valor_desconto: isset($data['valor_desconto']) ? (float) $data['valor_desconto'] : null,
            valor_outras_despesas: isset($data['valor_outras_despesas']) ? (float) $data['valor_outras_despesas'] : null,
            valor_total_ii: isset($data['valor_total_ii']) ? (float) $data['valor_total_ii'] : null,
            valor_ipi: isset($data['valor_ipi']) ? (float) $data['valor_ipi'] : null,
            valor_ipi_devolvido: isset($data['valor_ipi_devolvido']) ? (float) $data['valor_ipi_devolvido'] : null,
            valor_pis: isset($data['valor_pis']) ? (float) $data['valor_pis'] : null,
            valor_cofins: isset($data['valor_cofins']) ? (float) $data['valor_cofins'] : null,
            valor_total_nota: isset($data['valor_total_nota']) || isset($data['valor_total'])
                ? (float) ($data['valor_total_nota'] ?? $data['valor_total'])
                : null,
            informacoes_adicionais_contribuinte: $data['informacoes_adicionais_contribuinte'] ?? null,
            informacoes_adicionais_fisco: $data['informacoes_adicionais_fisco'] ?? null,
            documentos_referenciados: $data['documentos_referenciados'] ?? ($data['notas_referenciadas'] ?? null),
            indicador_intermed_transacao: $data['indicador_intermed_transacao'] ?? ($data['indicador_intermediario'] ?? null),
            cnpj_intermediador: $data['cnpj_intermediador'] ?? ($data['cnpj_intermediario'] ?? null),
            id_cadastro_intermediador: $data['id_cadastro_intermediador'] ?? ($data['id_intermediario'] ?? null),
            campos_extras: $data['campos_extras'] ?? [],
        );
    }

    /**
     * Serializa usando os nomes atuais da API e mescla campos fiscais ainda não tipados.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = parent::toArray();
        $camposExtras = $payload['campos_extras'] ?? [];

        unset($payload['campos_extras']);

        return array_merge($payload, $camposExtras);
    }
}
