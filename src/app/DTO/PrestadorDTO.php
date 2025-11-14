<?php

namespace Sysborg\FocusNfe\app\DTO;

use InvalidArgumentException;

class PrestadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $inscricao_municipal,
        public string $codigo_municipio
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do PrestadorDTO
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(): void
    {
        if (empty($this->cnpj)) {
            throw new InvalidArgumentException('O campo cnpj é obrigatório');
        }

        if (empty($this->inscricao_municipal)) {
            throw new InvalidArgumentException('O campo inscricao_municipal é obrigatório');
        }

        if (empty($this->codigo_municipio)) {
            throw new InvalidArgumentException('O campo codigo_municipio é obrigatório');
        }
    }

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
