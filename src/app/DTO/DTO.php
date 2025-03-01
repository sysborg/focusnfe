<?php

namespace Sysborg\FocusNFe\App\DTO;

abstract class DTO {
  /**
   * Transforma o objeto em array
   * 
   * @return array
   */
  public function toArray(): array
  {
      return get_object_vars($this);
  }
}