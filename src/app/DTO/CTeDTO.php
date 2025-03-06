<?php

namespace Sysborg\FocusNFe\app\DTO;

class CTEDTO extends DTO
{
    public function __construct(
        public string $referencia,
        public string $modal,
        public array $dadosGerais,
        public ?array $modalRodoviario = null,
        public ?array $modalAereo = null,
        public ?array $modalAquaviario = null,
        public ?array $modalFerroviario = null,
        public ?array $modalDutoviario = null,
        public ?array $modalMultimodal = null
    ) {}

    /**
     * Cria um objeto CTEDTO a partir de um array.
     * 
     * @param array $data
     * @return CTEDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['referencia'],
            $data['modal'],
            $data['dados_gerais'],
            $data['modal_rodoviario'] ?? null,
            $data['modal_aereo'] ?? null,
            $data['modal_aquaviario'] ?? null,
            $data['modal_ferroviario'] ?? null,
            $data['modal_dutoviario'] ?? null,
            $data['modal_multimodal'] ?? null
        );
    }
}
