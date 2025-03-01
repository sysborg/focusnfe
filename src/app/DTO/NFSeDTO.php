<?php

namespace App\DTO;

use Carbon\Carbon;

class NFSeDTO extends DTO {
    public function __construct(
        public Carbon $dataEmissao,
        public PrestadorDTO $prestador,
        public TomadorDTO $tomador,
        public ServicoDTO $servico
    ) {}

    /**
     * Cria um objeto NFSeDTO a partir de um array
     * 
     * @param array $data
     * @return NFSeDTO
     */
    public static function fromArray(array $data): self
    {
        $prestador = PrestadorDTO::fromArray($data['prestador']);
        $tomador = TomadorDTO::fromArray($data['tomador']);
        $servico = ServicoDTO::fromArray($data['servico']);

        $validatedData = self::validate($data);
        return new self(
            $data['dataEmissao'],
            $prestador,
            $tomador,
            $servico
        );
    }
}
