<?php

namespace Sysborg\FocusNfe\app\DTO;

class FerroviasDTO  extends DTO {
  public function __construct(
    public string $cnpj,
    public int $codigo_interno,
    public int $inscricao_estadual,
    public string $razao_social,
    public string $logradouro,
    public string $numero,
    public string $complemento,
    public string $bairro,
    public int $codigo_municipio,
    public string $municipio,
    public int $cep,
    public string $uf
  ) {}

  /**
   * Cria uma instância de FerroviasDTO a partir de um array
   * 
   * @param array $data
   * @return FerroviasDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['cnpj'],
      $data['codigo_interno'],
      $data['inscricao_estadual'],
      $data['razao_social'],
      $data['logradouro'],
      $data['numero'],
      $data['complemento'],
      $data['bairro'],
      $data['codigo_municipio'],
      $data['municipio'],
      $data['cep'],
      $data['uf']
    );
  }
}
