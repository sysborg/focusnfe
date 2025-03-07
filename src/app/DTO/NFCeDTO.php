<?php

namespace Sysborg\FocusNFe\app\DTO;

use Carbon\Carbon;

class NFCeDTO extends DTO {
    public function __construct(
        public string $natureza_operacao,
        public Carbon $data_emissao,
        public int $presenca_comprador,
        public string $cnpj_emitente,
        public int $modalidade_frete,
        public int $local_destino,
    ) {}

    /**
     * Cria um objeto NFCeDTO a partir de um array
     * 
     * @param array $data
     * @return NFCeDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['natureza_operacao'],
            new Carbon($data['data_emissao']),
            $data['presenca_comprador'],
            $data['cnpj_emitente'],
            $data['modalidade_frete'],
            $data['local_destino'],
        );
    }
}
