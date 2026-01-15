<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;

class NFSenDTO extends DTO 
{
    public function __construct(
        public Carbon $data_emissao,
        public PrestadorDTO $prestador,
        public TomadorDTO $tomador,
        public ServicoDTO $servico
    ) {}

    /**
     * Cria um objeto NFSeNDTO a partir de um array.
     * 
     * 
     * @param array $data
     * @return NFSeNDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            new Carbon($data['data_emissao']),
            PrestadorDTO::fromArray($data['prestador']),
            TomadorDTO::fromArray($data['tomador']),
            ServicoDTO::fromArray($data['servico'])
        );
    }
}
