<?php

namespace Sysborg\FocusNfe\app\DTO;

class CnpjEnderecoDTO extends DTO
{
    public function __construct(
        public ?string $codigo_municipio = null,
        public ?string $codigo_siafi = null,
        public ?string $codigo_ibge = null,
        public ?string $nome_municipio = null,
        public ?string $logradouro = null,
        public ?string $complemento = null,
        public ?string $numero = null,
        public ?string $bairro = null,
        public ?string $cep = null,
        public ?string $uf = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            codigo_municipio: $data['codigo_municipio'] ?? null,
            codigo_siafi: $data['codigo_siafi'] ?? null,
            codigo_ibge: $data['codigo_ibge'] ?? null,
            nome_municipio: $data['nome_municipio'] ?? null,
            logradouro: $data['logradouro'] ?? null,
            complemento: $data['complemento'] ?? null,
            numero: $data['numero'] ?? null,
            bairro: $data['bairro'] ?? null,
            cep: $data['cep'] ?? null,
            uf: $data['uf'] ?? null,
        );
    }
}
