<?php

namespace Sysborg\FocusNfe\app\DTO;

use InvalidArgumentException;

class ServicoDTO extends DTO
{
    public function __construct(
        public float $aliquota,
        public string $discriminacao,
        public bool $iss_retido,
        public string $item_lista_servico,
        public string $codigo_tributario_municipio,
        public float $valor_servicos,
        public ?string $codigo_cnae = null
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do ServicoDTO
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(): void
    {
        if ($this->aliquota < 0 || $this->aliquota > 100) {
            throw new InvalidArgumentException('A alíquota deve estar entre 0 e 100');
        }

        if (empty($this->discriminacao)) {
            throw new InvalidArgumentException('O campo discriminacao é obrigatório');
        }

        if (empty($this->item_lista_servico)) {
            throw new InvalidArgumentException('O campo item_lista_servico é obrigatório');
        }

        if (empty($this->codigo_tributario_municipio)) {
            throw new InvalidArgumentException('O campo codigo_tributario_municipio é obrigatório');
        }

        if ($this->valor_servicos <= 0) {
            throw new InvalidArgumentException('O valor dos serviços deve ser maior que zero');
        }
    }

    /**
     * Cria uma instância da classe ServicoDTO a partir de um array
     *
     * @param array $data
     * @return ServicoDTO
     */
    public static function fromArray(array $data): ServicoDTO
    {
        return new ServicoDTO(
            $data['aliquota'],
            $data['discriminacao'],
            $data['iss_retido'],
            $data['item_lista_servico'],
            $data['codigo_tributario_municipio'],
            $data['valor_servicos'],
            $data['codigo_cnae'] ?? null
        );
    }
}
