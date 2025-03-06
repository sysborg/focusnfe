<?php

namespace Sysborg\FocusNFe\app\DTO;

use Carbon\Carbon;

class NFCeDTO extends DTO {
    public function __construct(
        public string $naturezaOperacao,
        public Carbon $dataEmissao,
        public int $presencaComprador,
        public string $cnpjEmitente,
        public int $modalidadeFrete,
        public int $localDestino,
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
