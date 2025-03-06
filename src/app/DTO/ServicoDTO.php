<?php

namespace app\DTO;

class ServicoDTO
{
    public function __construct(
        public float $aliquota,
        public string $discriminacao,
        public bool $issRetido,
        public string $itemListaServico,
        public string $codigoTributarioMunicipio,
        public float $valorServicos
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
