<?php

namespace app\DTO;

class ContainersDTO extends DTO
{
  public function __construct(
    public string $identificacao,
    public array $lacres,
    public ?array $nfs,
    public ?array $nfes
  ) {}

  /**
   * Cria uma instância de ContainersDTO a partir de um array
   * 
   * @param array $data
   * @return ContainersDTO
   */
  public static function fromArray(array $data): self
  {
    $nfs = null;
    if (isset($data['nfs'])) {
      $nfs = array_map(fn($nf) => ContainersNFsDTO::fromArray($nf), $data['nfs']);
    }

    $nfes = null;
    if (isset($data['nfes'])) {
      $nfes = array_map(fn($nfe) => ContainersNFesDTO::fromArray($nfe), $data['nfes']);
    }

    return new self(
      $data['identificacao'],
      $data['lacres'],
      $nfs,
      $nfes
    );
  }
}
