<?php

namespace Sysborg\FocusNfe\app\DTO;

use InvalidArgumentException;

class TomadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $razao_social,
        public string $email,
        public EnderecoDTO $endereco
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do TomadorDTO
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(): void
    {
        if (empty($this->cnpj)) {
            throw new InvalidArgumentException('O campo cnpj é obrigatório');
        }

        if (empty($this->razao_social)) {
            throw new InvalidArgumentException('O campo razao_social é obrigatório');
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('O campo email é obrigatório e deve ser válido');
        }
    }

    /**
     * Cria um objeto TomadorDTO a partir de um array
     *
     * @param array $data
     * @return TomadorDTO
     */
    public static function fromArray(array $data): self
    {
        $endereco = EnderecoDTO::fromArray($data['endereco']);
        return new self(
            $data['cnpj'],
            $data['razao_social'],
            $data['email'],
            $endereco
        );
    }
}
