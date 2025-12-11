<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NFSeDTO extends DTO
{
    public function __construct(
        public Carbon $dataEmissao,
        public PrestadorDTO $prestador,
        public TomadorDTO $tomador,
        public ServicoDTO $servico
    ) {
        $this->validate();
    }

    /**
     * Valida os campos obrigatórios do DTO
     *
     * @throws ValidationException
     * @return void
     */
    protected function validate(): void
    {
        // Valida campos básicos
        $validator = Validator::make(get_object_vars($this), self::rules(), self::messages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Valida data de emissão
        if ($this->dataEmissao->isFuture()) {
            throw new ValidationException(
                Validator::make([], ['dataEmissao' => 'required'], ['dataEmissao.required' => 'A data de emissão não pode ser futura'])
            );
        }
    }

    /**
     * Regras de validação
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'dataEmissao' => 'required|date',
        ];
    }

    /**
     * Mensagens de validação customizadas
     *
     * @return array
     */
    public static function messages(): array
    {
        return [
            'dataEmissao.required' => 'A data de emissão é obrigatória',
            'dataEmissao.date' => 'A data de emissão deve ser uma data válida',
        ];
    }

    /**
     * Cria um objeto NFSeDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return NFSeDTO
     * @throws ValidationException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['dataEmissao'] instanceof Carbon ? $data['dataEmissao'] : new Carbon($data['dataEmissao']),
            PrestadorDTO::fromArray($data['prestador']),
            TomadorDTO::fromArray($data['tomador']),
            ServicoDTO::fromArray($data['servico'])
        );
    }

    /**
     * Converte o DTO para array aninhado em snake_case para enviar à API
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'data_emissao' => $this->dataEmissao->format('Y-m-d'),
            'prestador' => $this->prestador->toArray(),
            'tomador' => [
                'cnpj' => $this->tomador->cnpj,
                'razao_social' => $this->tomador->razaoSocial,
                'email' => $this->tomador->email,
                'endereco' => $this->tomador->endereco->toArray(),
            ],
            'servico' => $this->servico->toArray(),
        ];
    }
}
