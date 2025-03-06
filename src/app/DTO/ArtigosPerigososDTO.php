<?php

namespace app\DTO;

class ArtigosPerigososDTO extends DTO
{
  public function __construct(
    public string $numero_onu,
    public string $quantidade_total_volumes,
    public float $quantidade_total_artigos,
    public string $unidade_medida
  ) {}

  /**
     * Cria uma instância de ArtigosPerigososDTO a partir de um array
     * 
     * @param array $data
     * @return ArtigosPerigososDTO
     */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['numero_onu'],
      $data['quantidade_total_volumes'],
      $data['quantidade_total_artigos'],
      $data['unidade_medida']
    );
  }
}
