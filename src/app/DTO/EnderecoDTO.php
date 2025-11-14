<?php

namespace Sysborg\FocusNfe\app\DTO;

use InvalidArgumentException;

class EnderecoDTO extends DTO
{
    public function __construct(
        public string $logradouro,
        public string $numero,
        public string $complemento,
        public string $bairro,
        public string $codigo_municipio,
        public string $uf,
        public string $cep
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do EnderecoDTO
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(): void
    {
        if (empty($this->logradouro)) {
            throw new InvalidArgumentException('O campo logradouro é obrigatório');
        }

        if (empty($this->numero)) {
            throw new InvalidArgumentException('O campo numero é obrigatório');
        }

        if (empty($this->bairro)) {
            throw new InvalidArgumentException('O campo bairro é obrigatório');
        }

        if (empty($this->codigo_municipio)) {
            throw new InvalidArgumentException('O campo codigo_municipio é obrigatório');
        }

        if (empty($this->uf) || strlen($this->uf) !== 2) {
            throw new InvalidArgumentException('O campo uf é obrigatório e deve ter 2 caracteres');
        }

        if (empty($this->cep)) {
            throw new InvalidArgumentException('O campo cep é obrigatório');
        }
    }

    /**
     * Cria uma instância de EnderecoDTO a partir de um array
     *
     * @param array $data
     * @return EnderecoDTO
     */
    public static function fromArray(array $data): EnderecoDTO
    {
        return new EnderecoDTO(
            $data['logradouro'],
            $data['numero'],
            $data['complemento'] ?? '',
            $data['bairro'],
            $data['codigo_municipio'],
            $data['uf'],
            $data['cep']
        );
    }
}
