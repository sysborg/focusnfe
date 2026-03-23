<?php

namespace Sysborg\FocusNfe\app\DTO;

class CnpjResponseDTO extends DTO
{
    public function __construct(
        public ?string $razao_social = null,
        public ?string $cnpj = null,
        public ?string $situacao_cadastral = null,
        public ?string $cnae_principal = null,
        public ?bool $optante_simples_nacional = null,
        public ?bool $optante_mei = null,
        public ?CnpjEnderecoDTO $endereco = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            razao_social: $data['razao_social'] ?? null,
            cnpj: $data['cnpj'] ?? null,
            situacao_cadastral: $data['situacao_cadastral'] ?? null,
            cnae_principal: $data['cnae_principal'] ?? null,
            optante_simples_nacional: $data['optante_simples_nacional'] ?? null,
            optante_mei: $data['optante_mei'] ?? null,
            endereco: isset($data['endereco']) && is_array($data['endereco'])
                ? CnpjEnderecoDTO::fromArray($data['endereco'])
                : null,
        );
    }
}
