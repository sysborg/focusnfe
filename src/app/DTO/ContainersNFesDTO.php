<?php

namespace app\DTO;

class ContainersNFesDTO extends DTO
{
  public function __construct(
    public string $chave_nfe,
    public float $unidade_medida_rateada,
  ) {}

  /**
   * Cria uma instância de ContainersNFesDTO a partir de um array
   * 
   * @param array $data
   * @return ContainersNFesDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['chave_nfe'],
      $data['unidade_medida_rateada'],
    );
  }
}
