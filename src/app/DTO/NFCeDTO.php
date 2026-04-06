<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;

/**
 * DTO para emissão de NFC-e (Nota Fiscal de Consumidor Eletrônica)
 *
 * Cobre todos os campos documentados na API FocusNFe v2 para NFC-e 4.0,
 * incluindo campos obrigatórios de formas_pagamento e itens.
 */
class NFCeDTO extends DTO
{
    public function __construct(
        // Identificação
        public string $natureza_operacao,
        public Carbon $data_emissao,
        public int $presenca_comprador,       // 1=presencial, 2=internet, 3=teleatendimento, 4=entrega domiciliar, 9=outros
        public int $modalidade_frete,         // 9=sem frete (padrão NFC-e)
        public int $local_destino,            // 1=operação interna

        // Emitente
        public string $cnpj_emitente,
        public int $regime_tributario_emitente, // 1=simples, 2=simples excesso, 3=normal
        public string $logradouro_emitente,
        public string $numero_emitente,
        public string $bairro_emitente,
        public string $municipio_emitente,
        public string $uf_emitente,
        public string $cep_emitente,

        // Itens (obrigatório)
        public array $itens,

        // Formas de pagamento (obrigatório NFC-e 4.0)
        // Cada item: {forma_pagamento, valor_pagamento, ?cnpj_credenciadora, ?bandeira_operadora, ?numero_autorizacao}
        public array $formas_pagamento,

        // Destinatário (opcional em NFC-e)
        public ?string $cpf_destinatario = null,
        public ?string $nome_destinatario = null,
        public ?string $logradouro_destinatario = null,
        public ?string $numero_destinatario = null,
        public ?string $bairro_destinatario = null,
        public ?string $municipio_destinatario = null,
        public ?string $uf_destinatario = null,
        public ?string $cep_destinatario = null,

        // Complemento do emitente
        public ?string $complemento_emitente = null,
        public ?string $telefone_emitente = null,
        public ?string $email_emitente = null,
        public ?string $inscricao_estadual_emitente = null,
        public ?string $nome_fantasia_emitente = null,

        // Totais (calculados automaticamente pela FocusNFe, mas podem ser informados)
        public ?float $valor_total_nota = null,
        public ?float $valor_total_produtos = null,
        public ?float $valor_desconto = null,
        public ?float $valor_frete = null,
        public ?float $valor_seguro = null,
        public ?float $valor_outras_despesas = null,

        // Informações adicionais
        public ?string $informacoes_adicionais_contribuinte = null,

        // Intermediador de transação (Reforma Tributária)
        public ?string $indicador_intermed_transacao = null, // 0=sem intermediador, 1=com intermediador
        public ?string $cnpj_intermediador = null,
        public ?string $id_cadastro_intermediador = null,
    ) {
    }

    protected function specialCases(): array
    {
        return [
            'data_emissao' => fn (Carbon $v) => $v->utc()->toIso8601String(),
        ];
    }

    /**
     * Cria um NFCeDTO a partir de um array
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            natureza_operacao: $data['natureza_operacao'],
            data_emissao: new Carbon($data['data_emissao']),
            presenca_comprador: (int) $data['presenca_comprador'],
            modalidade_frete: (int) ($data['modalidade_frete'] ?? 9),
            local_destino: (int) ($data['local_destino'] ?? 1),
            cnpj_emitente: $data['cnpj_emitente'],
            regime_tributario_emitente: (int) $data['regime_tributario_emitente'],
            logradouro_emitente: $data['logradouro_emitente'],
            numero_emitente: $data['numero_emitente'],
            bairro_emitente: $data['bairro_emitente'],
            municipio_emitente: $data['municipio_emitente'],
            uf_emitente: $data['uf_emitente'],
            cep_emitente: $data['cep_emitente'],
            itens: $data['itens'],
            formas_pagamento: $data['formas_pagamento'],
            cpf_destinatario: $data['cpf_destinatario'] ?? null,
            nome_destinatario: $data['nome_destinatario'] ?? null,
            logradouro_destinatario: $data['logradouro_destinatario'] ?? null,
            numero_destinatario: $data['numero_destinatario'] ?? null,
            bairro_destinatario: $data['bairro_destinatario'] ?? null,
            municipio_destinatario: $data['municipio_destinatario'] ?? null,
            uf_destinatario: $data['uf_destinatario'] ?? null,
            cep_destinatario: $data['cep_destinatario'] ?? null,
            complemento_emitente: $data['complemento_emitente'] ?? null,
            telefone_emitente: $data['telefone_emitente'] ?? null,
            email_emitente: $data['email_emitente'] ?? null,
            inscricao_estadual_emitente: $data['inscricao_estadual_emitente'] ?? null,
            nome_fantasia_emitente: $data['nome_fantasia_emitente'] ?? null,
            valor_total_nota: isset($data['valor_total_nota']) ? (float) $data['valor_total_nota'] : null,
            valor_total_produtos: isset($data['valor_total_produtos']) ? (float) $data['valor_total_produtos'] : null,
            valor_desconto: isset($data['valor_desconto']) ? (float) $data['valor_desconto'] : null,
            valor_frete: isset($data['valor_frete']) ? (float) $data['valor_frete'] : null,
            valor_seguro: isset($data['valor_seguro']) ? (float) $data['valor_seguro'] : null,
            valor_outras_despesas: isset($data['valor_outras_despesas']) ? (float) $data['valor_outras_despesas'] : null,
            informacoes_adicionais_contribuinte: $data['informacoes_adicionais_contribuinte'] ?? null,
            indicador_intermed_transacao: $data['indicador_intermed_transacao'] ?? null,
            cnpj_intermediador: $data['cnpj_intermediador'] ?? null,
            id_cadastro_intermediador: $data['id_cadastro_intermediador'] ?? null,
        );
    }
}
