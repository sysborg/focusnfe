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
        $calculatedValue = $value;
        $specialCases = $this->specialCases ?? null;

        if (is_array($specialCases) && isset($specialCases[$key]) && is_callable($specialCases[$key])) {
            $calculatedValue = $specialCases[$key]($value);
        }

        if (isset($mapping[$key])) {
            $result[$mapping[$key]] = $calculatedValue;
        } else {
            $snakeKey = $this->camelToSnake($key);
            $result[$snakeKey] = $calculatedValue;
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
