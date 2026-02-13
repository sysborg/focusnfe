<?php

namespace Sysborg\FocusNfe\app\DTO;
use Carbon\Carbon;

class ModalAereoDTO extends DTO
{
  public function __construct(
    public int $numero_minuta,
    public int $numero_operacional,
    public Carbon $data_prevista_entrega,
    public string $dimensao_carga,
    public array $informacoes_manuseio,
    public string $classe_tarifa,
    public string $codigo_tarifa,
    public float $valor_tarifa,
    public ?array $artigos_perigosos
  ) {}

  /**
     * Cria uma instância de ModalAereoDTO a partir de um array
     * 
     * @param array $data
     * @return ModalAereoDTO
     */
  public static function fromArray(array $data): self
  {
    $artigos_perigosos = null;
    if (isset($data['artigos_perigosos'])) {
      foreach ($data['artigos_perigosos'] as $artigo_perigoso) {
        $artigos_perigosos[] = ArtigosPerigososDTO::fromArray($artigo_perigoso);
      }
    }

    return new self(
      $data['numero_minuta'],
      $data['numero_operacional'],
      Carbon::parse($data['data_prevista_entrega']),
      $data['dimensao_carga'],
      $data['informacoes_manuseio'],
      $data['classe_tarifa'],
      $data['codigo_tarifa'],
      $data['valor_tarifa'],
      $artigos_perigosos
    );
  }
}
