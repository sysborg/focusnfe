<?php

namespace app\DTO;
use Carbon\Carbon;

class ModalDutoviarioDTO extends DTO
{
  public function __construct(
    public float $valor_tarifa,
    public string $data_inicio,
    public Carbon $data_fim
  ) {}

  /**
     * Cria uma instância de ModalDutoviarioDTO a partir de um array
     * 
     * @param array $data
     * @return ModalDutoviarioDTO
     */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['valor_tarifa'],
      $data['data_inicio'],
      Carbon::parse($data['data_fim'])
    );
  }
}
