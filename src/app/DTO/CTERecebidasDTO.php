<?php

namespace Sysborg\FocusNFe\app\DTO;

class CTERecebidasDTO {
    public function __construct(
        public string $observacoes
    ) {}

  
    public static function fromArray(array $data): self
    {
        return new self(
            $data['observacoes']
        );
    }
}
