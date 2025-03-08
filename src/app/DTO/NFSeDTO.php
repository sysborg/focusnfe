<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;
use Sysborg\FocusNFe\App\DTO\ServicoDTO;

class NFSeDTO extends DTO {
    public function __construct(
        public Carbon $data_emissao,
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

        return new self(
            new Carbon($data['data_emissao']),
            $prestador,
            $tomador,
            $servico
        );
    }
}
