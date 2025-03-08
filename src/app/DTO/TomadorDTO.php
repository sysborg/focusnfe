<?php

namespace Sysborg\FocusNfe\app\DTO;
use App\Helpers\Validations;

class TomadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $razao_social,
        public string $email,
        public EnderecoDTO $endereco
    ) {}

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
