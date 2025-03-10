<?php

namespace Sysborg\FocusNfe\app\DTO;

class MDFeDTO extends DTO
{
    public function __construct(
        public int $emitente,
        public int $serie,
        public int $numero,
        public string $uf_inicio,
        public string $uf_fim,
        public string $cnpj_emitente,
        public ?string $cpf_emitente,
        public string $inscricao_estadual_emitente,
        public string $nome_emitente,
        public ?string $nome_fantasia_emitente,
        public string $logradouro_emitente,
        public string $numero_emitente,
        public string $bairro_emitente,
        public int $codigo_municipio_emitente,
        public string $municipio_emitente,
        public string $uf_emitente,
        public float $valor_total_carga,
        public int $codigo_unidade_medida_peso_bruto
    ) {}

    /**
     * Cria uma instância de MDFeDTO a partir de um array
     * 
     * @param array $data
     * @return MDFeDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['emitente'],
            $data['serie'],
            $data['numero'],
            $data['uf_inicio'],
            $data['uf_fim'],
            $data['cnpj_emitente'],
            $data['cpf_emitente'] ?? null,
            $data['inscricao_estadual_emitente'],
            $data['nome_emitente'],
            $data['nome_fantasia_emitente'] ?? null,
            $data['logradouro_emitente'],
            $data['numero_emitente'],
            $data['bairro_emitente'],
            $data['codigo_municipio_emitente'],
            $data['municipio_emitente'],
            $data['uf_emitente'],
            $data['valor_total_carga'],
            $data['codigo_unidade_medida_peso_bruto']
        );
    }
}
