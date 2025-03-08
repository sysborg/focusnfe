<?php

namespace Sysborg\FocusNfe\app\DTO;

class ModalMultimodalDTO extends DTO
{
  public function __construct(
    public float $numero_certificado_operador,
    public string $indicador_negociavel,
    public string $nome_seguradora,
    public string $cnpj_seguradora,
    public string $numero_apolice_seguro,
    public string $numero_averbacao_seguro
  ) {}

  /**
     * Cria uma instância de ModalMultimodalDTO a partir de um array
     * 
     * @param array $data
     * @return ModalMultimodalDTO
     */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['numero_certificado_operador'],
      $data['indicador_negociavel'],
      $data['nome_seguradora'],
      $data['cnpj_seguradora'],
      $data['numero_apolice_seguro'],
      $data['numero_averbacao_seguro']
    );
  }
}
