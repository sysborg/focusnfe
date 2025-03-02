<?php

namespace Sysborg\FocusNfe\app\DTO;

class EnderecoDTO extends DTO
{
    public function __construct(
        public string $logradouro,
        public string $numero,
        public string $complemento,
        public string $bairro,
        public string $codigoMunicipio,
        public string $uf,
        public string $cep
    ) {}

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
            $data['complemento'],
            $data['bairro'],
            $data['codigo_municipio'],
            $data['uf'],
            $data['cep']
        );
    }
}
