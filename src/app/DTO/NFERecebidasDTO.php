<?php

namespace Sysborg\FocusNFe\app\DTO;

use Sysborg\FocusNfe\app\DTO\DTO;

class NFeRecebidasDTO extends DTO
{
    public function __construct(
        public string $tipo,
        public string $justificativa
    ) {}

 
    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipo'],
            $data['justificativa']
        );
    }
}
