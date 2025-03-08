<?php

namespace Sysborg\FocusNfe\app\DTO;

class ServicoDTO extends DTO
{
    public function __construct(
        public float $aliquota,
        public string $discriminacao,
        public bool $iss_retido,
        public string $item_lista_servico,
        public string $codigo_tributario_municipio,
        public float $valor_servicos
    ) {}

    /**
     * Cria uma instância da classe ServicoDTO a partir de um array
     * 
     * @param array $data
     * @return ServicoDTO
     */
    public static function fromArray(array $data): ServicoDTO
    {
        return new ServicoDTO(
            $data['aliquota'],
            $data['discriminacao'],
            $data['iss_retido'],
            $data['item_lista_servico'],
            $data['codigo_tributario_municipio'],
            $data['valor_servicos']
        );
    }
}
