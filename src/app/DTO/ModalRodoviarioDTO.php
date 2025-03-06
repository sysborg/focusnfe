<?php

namespace app\DTO;

class ModalRodoviarioDTO extends DTO
{
  public function __construct(
    public string $rntrc,
    public ?array $ordens_coleta_associados
  ) {}

  /**
     * Cria uma instância de ModalRodoviarioDTO a partir de um array
     * 
     * @param array $data
     * @return ModalRodoviarioDTO
     */
  public static function fromArray(array $data): self
  {
    $ordens = null;
    if (isset($data['ordens_coleta_associados'])) {
      $ordens = array_map(fn($ordem) => OrdemColetaDTO::fromArray($ordem), $data['ordens_coleta_associados']);
    }

    return new self(
      $data['rntrc'],
      $ordens
    );
  }
}