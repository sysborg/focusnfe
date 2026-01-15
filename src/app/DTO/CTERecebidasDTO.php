<?php

namespace Sysborg\FocusNfe\app\DTO;

class CTERecebidasDTO  extends DTO {
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
