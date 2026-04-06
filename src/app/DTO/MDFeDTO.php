<?php

namespace Sysborg\FocusNfe\app\DTO;

class MDFeDTO extends DTO
{
    public function __construct(
        // Identificação do emitente (ID da empresa no Focus NFe)
        public int $emitente,
        public int $serie,
        public int $numero,
        public string $ufInicio,
        public string $ufFim,
        public string $cnpjEmitente,
        public string $inscricaoEstadualEmitente,
        public string $valorTotalCarga,
        public string $codigoUnidadeMedidaPesoBruto,
        // Campos de emitente opcionais (podem vir do cadastro da empresa)
        public ?string $cpfEmitente = null,
        public ?string $nomeEmitente = null,
        public ?string $nomeFantasiaEmitente = null,
        public ?string $logradouroEmitente = null,
        public ?string $numeroEmitente = null,
        public ?string $bairroEmitente = null,
        public ?int $codigoMunicipioEmitente = null,
        public ?string $municipioEmitente = null,
        public ?string $ufEmitente = null,
        // Campos de transporte
        public ?string $tipoTransporte = null,
        public ?string $modoTransporte = null,
        public ?string $dataEmissao = null,
        public ?string $dataHoraPrevistInicioViagem = null,
        // Municípios e percurso
        public ?array $municipiosCarregamento = null,
        public ?array $municipiosDescarregamento = null,
        public ?array $percursos = null,
        // Totais
        public ?int $quantidadeTotalCte = null,
        public ?string $pesoBruto = null,
        // Modais (apenas um deve ser informado)
        public ?array $modalRodoviario = null,
        public ?array $modalAereo = null,
        public ?array $modalAquaviario = null,
        public ?array $modalFerroviario = null,
    ) {
    }

    /**
     * Mapeamentos de campos que fogem da conversão automática camelCase → snake_case
     */
    protected static function fieldMapping(): array
    {
        return [
            'dataHoraPrevistInicioViagem' => 'data_hora_previsto_inicio_viagem',
        ];
    }

    /**
     * Cria uma instância de MDFeDTO a partir de um array (aceita camelCase ou snake_case)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            emitente: (int) $data['emitente'],
            serie: (int) $data['serie'],
            numero: (int) $data['numero'],
            ufInicio: $data['ufInicio'] ?? $data['uf_inicio'],
            ufFim: $data['ufFim'] ?? $data['uf_fim'],
            cnpjEmitente: $data['cnpjEmitente'] ?? $data['cnpj_emitente'],
            inscricaoEstadualEmitente: $data['inscricaoEstadualEmitente'] ?? $data['inscricao_estadual_emitente'],
            valorTotalCarga: (string) ($data['valorTotalCarga'] ?? $data['valor_total_carga']),
            codigoUnidadeMedidaPesoBruto: (string) ($data['codigoUnidadeMedidaPesoBruto'] ?? $data['codigo_unidade_medida_peso_bruto']),
            cpfEmitente: $data['cpfEmitente'] ?? $data['cpf_emitente'] ?? null,
            nomeEmitente: $data['nomeEmitente'] ?? $data['nome_emitente'] ?? null,
            nomeFantasiaEmitente: $data['nomeFantasiaEmitente'] ?? $data['nome_fantasia_emitente'] ?? null,
            logradouroEmitente: $data['logradouroEmitente'] ?? $data['logradouro_emitente'] ?? null,
            numeroEmitente: $data['numeroEmitente'] ?? $data['numero_emitente'] ?? null,
            bairroEmitente: $data['bairroEmitente'] ?? $data['bairro_emitente'] ?? null,
            codigoMunicipioEmitente: isset($data['codigoMunicipioEmitente']) ? (int) $data['codigoMunicipioEmitente'] : (isset($data['codigo_municipio_emitente']) ? (int) $data['codigo_municipio_emitente'] : null),
            municipioEmitente: $data['municipioEmitente'] ?? $data['municipio_emitente'] ?? null,
            ufEmitente: $data['ufEmitente'] ?? $data['uf_emitente'] ?? null,
            tipoTransporte: $data['tipoTransporte'] ?? $data['tipo_transporte'] ?? null,
            modoTransporte: $data['modoTransporte'] ?? $data['modo_transporte'] ?? null,
            dataEmissao: $data['dataEmissao'] ?? $data['data_emissao'] ?? null,
            dataHoraPrevistInicioViagem: $data['dataHoraPrevistInicioViagem'] ?? $data['data_hora_previsto_inicio_viagem'] ?? null,
            municipiosCarregamento: $data['municipiosCarregamento'] ?? $data['municipios_carregamento'] ?? null,
            municipiosDescarregamento: $data['municipiosDescarregamento'] ?? $data['municipios_descarregamento'] ?? null,
            percursos: $data['percursos'] ?? null,
            quantidadeTotalCte: isset($data['quantidadeTotalCte']) ? (int) $data['quantidadeTotalCte'] : (isset($data['quantidade_total_cte']) ? (int) $data['quantidade_total_cte'] : null),
            pesoBruto: isset($data['pesoBruto']) ? (string) $data['pesoBruto'] : (isset($data['peso_bruto']) ? (string) $data['peso_bruto'] : null),
            modalRodoviario: $data['modalRodoviario'] ?? $data['modal_rodoviario'] ?? null,
            modalAereo: $data['modalAereo'] ?? $data['modal_aereo'] ?? null,
            modalAquaviario: $data['modalAquaviario'] ?? $data['modal_aquaviario'] ?? null,
            modalFerroviario: $data['modalFerroviario'] ?? $data['modal_ferroviario'] ?? null,
        );
    }
}
