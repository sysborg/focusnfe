<?php

namespace Sysborg\FocusNfe\app\DTO;

class PrestadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $inscricao_municipal,
        public string $codigo_municipio
    ) {}

    /**
     * Cria um objeto PrestadorDTO a partir de um array
     * 
     * @param array $data
     * @return PrestadorDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['cnpj'],
            $data['inscricao_municipal'],
            $data['codigo_municipio']
        );
    }
}
