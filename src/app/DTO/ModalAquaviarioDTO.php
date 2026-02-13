<?php

namespace Sysborg\FocusNfe\app\DTO;

class ModalAquaviarioDTO extends DTO
{
  public function __construct(
    public float $valor_prestacao,
    public float $adicional_frete_renovacao_marinha,
    public string $identificacao_navio,
    public array $balsas,
    public int $numero_viagem,
    public string $direcao,
    public string $irin_navio,
    public ?array $containers,
    public string $tipo_navegacao
  ) {}

  /**
     * Cria uma instância de ModalAquaviarioDTO a partir de um array
     * 
     * @param array $data
     * @return ModalAquaviarioDTO
     */
  public static function fromArray(array $data): self
  {
    $containers = null;
    if (isset($data['containers'])) {
      $containers = array_map(fn($container) => ContainersDTO::fromArray($container), $data['containers']);
    }

    return new self(
      $data['valor_prestacao'],
      $data['adicional_frete_renovacao_marinha'],
      $data['identificacao_navio'],
      $data['balsas'],
      $data['numero_viagem'],
      $data['direcao'],
      $data['irin_navio'],
      $containers,
      $data['tipo_navegacao']
    );
  }
}
