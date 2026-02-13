<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EnderecoDTO extends DTO
{
    public function __construct(
        public string $logradouro,
        public string $numero,
        public string $complemento,
        public string $bairro,
        public string $codigoMunicipio,
        public string $uf,
        public string $cep
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do EnderecoDTO
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
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'codigoMunicipio' => 'required|string|max:10',
            'uf' => 'required|string|size:2',
            'cep' => 'required|string|max:10',
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
            'logradouro.required' => 'O logradouro é obrigatório',
            'logradouro.string' => 'O logradouro deve ser um texto',
            'logradouro.max' => 'O logradouro não pode ter mais de 255 caracteres',
            'numero.required' => 'O número é obrigatório',
            'numero.string' => 'O número deve ser um texto',
            'numero.max' => 'O número não pode ter mais de 10 caracteres',
            'complemento.string' => 'O complemento deve ser um texto',
            'complemento.max' => 'O complemento não pode ter mais de 100 caracteres',
            'bairro.required' => 'O bairro é obrigatório',
            'bairro.string' => 'O bairro deve ser um texto',
            'bairro.max' => 'O bairro não pode ter mais de 100 caracteres',
            'codigoMunicipio.required' => 'O código do município é obrigatório',
            'codigoMunicipio.string' => 'O código do município deve ser um texto',
            'codigoMunicipio.max' => 'O código do município não pode ter mais de 10 caracteres',
            'uf.required' => 'A UF é obrigatória',
            'uf.string' => 'A UF deve ser um texto',
            'uf.size' => 'A UF deve ter exatamente 2 caracteres',
            'cep.required' => 'O CEP é obrigatório',
            'cep.string' => 'O CEP deve ser um texto',
            'cep.max' => 'O CEP não pode ter mais de 10 caracteres',
        ];
    }

    /**
     * Cria uma instância de EnderecoDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return EnderecoDTO
     */
    public static function fromArray(array $data): EnderecoDTO
    {
        return new EnderecoDTO(
            $data['logradouro'],
            $data['numero'],
            $data['complemento'] ?? '',
            $data['bairro'],
            $data['codigoMunicipio'],
            $data['uf'],
            $data['cep']
        );
    }
}
