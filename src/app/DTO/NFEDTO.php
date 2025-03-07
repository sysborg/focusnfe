<?php

namespace Sysborg\FocusNFe\app\DTO;

use Carbon\Carbon;

class NFeDTO extends DTO {
    public function __construct(
        public string $natureza_operacao,
        public Carbon $data_emissao,
        public int $tipo_documento,
        public int $local_destino,
        public int $finalidade_emissao,
        public int $consumidor_final,
        public int $presenca_comprador,
        public string $cnpj_emitente,
        public string $cpf_emitente,
        public string $inscricao_estadual_emitente,
        public string $logradouro_emitente,
        public string $numero_emitente,
        public string $bairro_emitente,
        public string $municipio_emitente,
        public string $uf_emitente,
        public int $regime_tributario_emitente,
        public string $nome_destinatario,
        public ?string $cnpj_destinatario,
        public ?string $cpf_destinatario,
        public ?string $inscricao_estadual_destinatario,
        public string $logradouro_destinatario,
        public string $numero_destinatario,
        public string $bairro_destinatario,
        public string $municipio_destinatario,
        public string $uf_destinatario,
        public int $indicador_inscricao_estadual_destinatario,
        public array $itens
    ) {}

 
    public static function fromArray(array $data): self
    {
        return new self(
            $data['natureza_operacao'],
            new Carbon($data['data_emissao']),
            $data['tipo_documento'],
            $data['local_destino'],
            $data['finalidade_emissao'],
            $data['consumidor_final'],
            $data['presenca_comprador'],
            $data['cnpj_emitente'],
            $data['cpf_emitente'] ?? '',
            $data['inscricao_estadual_emitente'],
            $data['logradouro_emitente'],
            $data['numero_emitente'],
            $data['bairro_emitente'],
            $data['municipio_emitente'],
            $data['uf_emitente'],
            $data['regime_tributario_emitente'],
            $data['nome_destinatario'],
            $data['cnpj_destinatario'] ?? null,
            $data['cpf_destinatario'] ?? null,
            $data['inscricao_estadual_destinatario'] ?? null,
            $data['logradouro_destinatario'],
            $data['numero_destinatario'],
            $data['bairro_destinatario'],
            $data['municipio_destinatario'],
            $data['uf_destinatario'],
            $data['indicador_inscricao_estadual_destinatario'],
            $data['itens']
        );
    }
}
