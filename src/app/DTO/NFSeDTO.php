<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;
use InvalidArgumentException;
use Sysborg\FocusNFe\app\DTO\ServicoDTO;

class NFSeDTO extends DTO {
    public function __construct(
        public Carbon $data_emissao,
        public PrestadorDTO $prestador,
        public TomadorDTO $tomador,
        public ServicoDTO $servico
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do NFSeDTO
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(): void
    {
        if ($this->data_emissao->isFuture()) {
            throw new InvalidArgumentException('A data de emissão não pode ser futura');
        }
    }

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
