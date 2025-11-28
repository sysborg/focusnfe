<?php

namespace Sysborg\FocusNfe\app\DTO;

abstract class DTO {
  /**
   * Mapeamento de campos que não seguem a conversão automática
   * Sobrescreva este método nas classes filhas para definir exceções
   *
   * @return array ['campoOriginal' => 'campo_destino']
   */
  protected static function fieldMapping(): array
  {
      return [];
  }

  /**
   * Transforma o objeto em array convertendo camelCase para snake_case
   * Usa o mapeamento de exceções quando disponível
   *
   * @return array
   */
  public function toArray(): array
  {
      $data = get_object_vars($this);
      $mapping = static::fieldMapping();
      $result = [];

      foreach ($data as $key => $value) {
          // Se existe mapeamento específico, usa ele
          if (isset($mapping[$key])) {
              $result[$mapping[$key]] = $value;
          } else {
              // Senão, converte automaticamente para snake_case
              $snakeKey = $this->camelToSnake($key);
              $result[$snakeKey] = $value;
          }
      }

      return $result;
  }

  /**
   * Converte camelCase para snake_case
   *
   * @param string $string
   * @return string
   */
  private function camelToSnake(string $string): string
  {
      return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
  }
}
