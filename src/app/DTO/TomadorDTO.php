<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TomadorDTO extends DTO
{
    public function __construct(
        public string $cnpj,
        public string $razaoSocial,
        public string $email,
        public EnderecoDTO $endereco
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do TomadorDTO
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
            'razaoSocial' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
            'cnpj.required' => 'O CNPJ do tomador é obrigatório',
            'cnpj.string' => 'O CNPJ do tomador deve ser um texto',
            'cnpj.cnpj' => 'O CNPJ do tomador é inválido',
            'razaoSocial.required' => 'A razão social do tomador é obrigatória',
            'razaoSocial.string' => 'A razão social do tomador deve ser um texto',
            'razaoSocial.max' => 'A razão social do tomador não pode ter mais de 255 caracteres',
            'email.required' => 'O email do tomador é obrigatório',
            'email.email' => 'O email do tomador deve ser válido',
            'email.max' => 'O email do tomador não pode ter mais de 255 caracteres',
        ];
    }

    /**
     * Cria um objeto TomadorDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return TomadorDTO
     */
    public static function fromArray(array $data): self
    {
        $endereco = EnderecoDTO::fromArray($data['endereco']);
        return new self(
            $data['cnpj'],
            $data['razaoSocial'],
            $data['email'],
            $endereco
        );
    }
}
