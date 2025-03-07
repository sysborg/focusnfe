<?php

namespace Sysborg\FocusNfe\app\DTO;
use Carbon\Carbon;

class OrdensColetaAssociadosDTO  extends DTO
{
  public function __construct(
      public string $serie,
      public int $numero_ordem_coleta,
      public Carbon $data_emissao,
      public string $cnpj,
      public string $codigo_interno,
      public string $inscricao_estadual,
      public string $uf,
      public string $telefone
  ) {}

  /**
   * Cria uma instância da classe OrdensColetaAssociadosDTO a partir de um array
   * 
   * @param array $data
   * @return OrdensColetaAssociadosDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['serie'],
      $data['numero_ordem_coleta'],
      Carbon::parse($data['data_emissao']),
      $data['cnpj'],
      $data['codigo_interno'],
      $data['inscricao_estadual'],
      $data['uf'],
      $data['telefone']   
    );
  }
}
