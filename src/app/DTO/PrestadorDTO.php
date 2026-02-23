<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PrestadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $inscricaoMunicipal,
        public string $codigoMunicipio,
        public bool $optanteSimplesNacional = true
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do PrestadorDTO
     *
     * @throws ValidationException
     * @return void
     */
    protected function validate(): void
    {
        $validator = Validator::make(get_object_vars($this), self::rules(), self::messages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
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
            'cnpj' => 'required|string|cnpj',
            'inscricaoMunicipal' => 'required|string|max:20',
            'codigoMunicipio' => 'required|string|max:10',
            'optanteSimplesNacional' => 'required|boolean',
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
            'cnpj.required' => 'O CNPJ do prestador é obrigatório',
            'cnpj.string' => 'O CNPJ do prestador deve ser um texto',
            'cnpj.cnpj' => 'O CNPJ do prestador é inválido',
            'inscricaoMunicipal.required' => 'A inscrição municipal é obrigatória',
            'inscricaoMunicipal.string' => 'A inscrição municipal deve ser um texto',
            'inscricaoMunicipal.max' => 'A inscrição municipal não pode ter mais de 20 caracteres',
            'codigoMunicipio.required' => 'O código do município é obrigatório',
            'codigoMunicipio.string' => 'O código do município deve ser um texto',
            'codigoMunicipio.max' => 'O código do município não pode ter mais de 10 caracteres',
            'optanteSimplesNacional.required' => 'O campo optante pelo Simples Nacional é obrigatório',
            'optanteSimplesNacional.boolean' => 'O campo optante pelo Simples Nacional deve ser verdadeiro ou falso',
        ];
    }

    /**
     * Cria um objeto PrestadorDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return PrestadorDTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['cnpj'],
            $data['inscricaoMunicipal'],
            $data['codigoMunicipio'],
            $data['optanteSimplesNacional'] ?? true
        );
    }
}
