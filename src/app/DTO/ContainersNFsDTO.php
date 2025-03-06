<?php

namespace app\DTO;

class ContainersNFsDTO extends DTO
{
  public function __construct(
    public string $serie,
    public string $numero_documento,
    public float $unidade_medida_rateada,
  ) {}

  /**
   * Cria uma instância de ContainersNFsDTO a partir de um array
   * 
   * @param array $data
   * @return ContainersNFsDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['serie'],
      $data['numero_documento'],
      $data['unidade_medida_rateada'],
    );
  }
}
