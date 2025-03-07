<?php

namespace Sysborg\FocusNFe\app\DTO;

use Sysborg\FocusNFe\app\DTO\ModalAereoDTO;
use Sysborg\FocusNFe\app\DTO\ModalAquaviarioDTO;
use Sysborg\FocusNFe\app\DTO\ModalDutoviarioDTO;
use Sysborg\FocusNFe\app\DTO\ModalMultimodalDTO;
use Sysborg\FocusNFe\app\DTO\ModalRodoviarioDTO;
use Sysborg\FocusNFe\app\DTO\ModalFerroviarioDTO;



class CTEDTO extends DTO
{
    public function __construct(
        public ?ModalAereoDTO $modal_aereo,
        public ?ModalAquaviarioDTO $modal_aquaviario,
        public ?ModalDutoviarioDTO $modal_dutoviario,
        public ?ModalFerroviarioDTO $modal_ferroviario,
        public ?ModalMultimodalDTO $modal_multimodal,
        public ?ModalRodoviarioDTO $modal_rodoviario,
        public $referencia
    ) {}

    /**
     * Cria um objeto CTEDTO a partir de um array.
     * 
     * @param array $data
     * @return CTEDTO
     */
    public static function fromArray(array $data): self
    {
        $modal_aereo = null;
        if (isset($data['modal_aereo'])) {
            $modal_aereo = ModalAereoDTO::fromArray($data['modal_aereo']);
        }

        $modal_aquaviario = null;
        if (isset($data['modal_aquaviario'])) {
            $modal_aquaviario = ModalAquaviarioDTO::fromArray($data['modal_aquaviario']);
        }

        $modal_dutoviario = null;
        if (isset($data['modal_dutoviario'])) {
            $modal_dutoviario = ModalDutoviarioDTO::fromArray($data['modal_dutoviario']);
        }

        $modal_ferroviario = null;
        if (isset($data['modal_ferroviario'])) {
            $modal_ferroviario = ModalFerroviarioDTO::fromArray($data['modal_ferroviario']);
        }

        $modal_multimodal = null;
        if (isset($data['modal_multimodal'])) {
            $modal_multimodal = ModalMultimodalDTO::fromArray($data['modal_multimodal']);
        }

        $modal_rodoviario = null;
        if (isset($data['modal_rodoviario'])) {
            $modal_rodoviario = $data['modal_rodoviario']['tipo'] === 'CTe' ? 
                ModalRodoviarioDTO::fromArray($data['modal_rodoviario']) :
                ModalRodoviarioOSDTO::fromArray($data['modal_rodoviario']);
        }

        return new self(
            $data['referencia'],
            $modal_aereo,
            $modal_aquaviario,
            $modal_dutoviario,
            $modal_ferroviario,
            $modal_multimodal,
            $modal_rodoviario
        );
    }
}
