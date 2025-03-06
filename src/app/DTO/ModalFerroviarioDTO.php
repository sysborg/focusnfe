<?php

namespace app\DTO;

class ModalFerroviarioDTO extends DTO
{
  public function __construct(
    public int $tipo_trafego,
    public int $responsavel_faturamento,
    public int $ferrovia_emitente,
    public float $valor_frete_trafego_mutuo,
    public string $chave_cte_ferrovia_origem,
    public ?array $ferrovias,
    public string $fluxo_ferroviario
  ) {}

  /**
     * Cria uma instância de ModalFerroviarioDTO a partir de um array
     * 
     * @param array $data
     * @return ModalFerroviarioDTO
     */
  public static function fromArray(array $data): self
  {
    $ferrovias = null;
    if (isset($data['ferrovias'])) {
      $ferrovias = array_map(fn($ferrovia) => FerroviasDTO::fromArray($ferrovia), $data['ferrovias']);
    }

    return new self(
      $data['tipo_trafego'],
      $data['responsavel_faturamento'],
      $data['ferrovia_emitente'],
      $data['valor_frete_trafego_mutuo'],
      $data['chave_cte_ferrovia_origem'],
      $ferrovias,
      $data['fluxo_ferroviario']
    );
  }
}
