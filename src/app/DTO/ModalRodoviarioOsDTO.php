<?php

namespace Sysborg\FocusNFe\app\DTO;

use Carbon\Carbon;

class ModalRodoviarioOsDTO extends DTO {
  public function __construct(
    public int $taf,
    public int $numero_registro_estadual,
    public string $placa,
    public string $renavam,
    public int $cpf_proprietario,
    public int $cnpj_proprietario,
    public int $taf_proprietario,
    public int $numero_registro_estadual_proprietario,
    public string $razao_social_proprietario,
    public string $inscricao_estadual_proprietario,
    public string $uf_proprietario,
    public string $tipo_proprietario,
    public string $uf_licenciamento,
    public string $tipo_fretamento,
    public Carbon $data_viagem_fretamento
  ) {}

  /**
   * Cria um objeto ModalRodoviarioOsDTO a partir de um array
   * 
   * @param array $data
   * @return ModalRodoviarioOsDTO
   */
  public static function fromArray(array $data): self
  {
    return new self(
      $data['taf'],
      $data['numero_registro_estadual'],
      $data['placa'],
      $data['renavam'],
      $data['cpf_proprietario'],
      $data['cnpj_proprietario'],
      $data['taf_proprietario'],
      $data['numero_registro_estadual_proprietario'],
      $data['razao_social_proprietario'],
      $data['inscricao_estadual_proprietario'],
      $data['uf_proprietario'],
      $data['tipo_proprietario'],
      $data['uf_licenciamento'],
      $data['tipo_fretamento'],
      new Carbon($data['data_viagem_fretamento'])
    );
  }
}
