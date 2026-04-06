<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TomadorDTO extends DTO
{
    public function __construct(
        public string $razaoSocial,
        public EnderecoDTO $endereco,
        public ?string $cnpj = null,
        public ?string $cpf = null,
        public ?string $email = null,
        public ?string $telefone = null,
        public ?string $inscricaoMunicipal = null,
        public ?string $nif = null,
        public ?string $motivoAusenciaNif = null,
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
            'cnpj' => 'nullable|string',
            'cpf' => 'nullable|string',
            'razaoSocial' => 'required|string|max:115',
            'email' => 'nullable|email|max:80',
            'telefone' => 'nullable|string|max:11',
            'inscricaoMunicipal' => 'nullable|string',
            'nif' => 'nullable|string',
            'motivoAusenciaNif' => 'nullable|string|max:1',
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
            'cnpj.string' => 'O CNPJ do tomador deve ser um texto',
            'cpf.string' => 'O CPF do tomador deve ser um texto',
            'razaoSocial.required' => 'A razão social do tomador é obrigatória',
            'razaoSocial.string' => 'A razão social do tomador deve ser um texto',
            'razaoSocial.max' => 'A razão social do tomador não pode ter mais de 115 caracteres',
            'email.email' => 'O email do tomador deve ser válido',
            'email.max' => 'O email do tomador não pode ter mais de 80 caracteres',
            'telefone.max' => 'O telefone do tomador não pode ter mais de 11 caracteres',
            'motivoAusenciaNif.max' => 'O motivo de ausência de NIF não pode ter mais de 1 caractere',
        ];
    }

    /**
     * Cria um objeto TomadorDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return TomadorDTO
     */
    /**
     * Converte o DTO para array snake_case para enviar à API
     */
    public function toArray(): array
    {
        $result = [
            'razao_social' => $this->razaoSocial,
            'endereco' => $this->endereco->toArray(),
        ];

        if ($this->cnpj !== null) {
            $result['cnpj'] = $this->cnpj;
        }
        if ($this->cpf !== null) {
            $result['cpf'] = $this->cpf;
        }
        if ($this->email !== null) {
            $result['email'] = $this->email;
        }
        if ($this->telefone !== null) {
            $result['telefone'] = $this->telefone;
        }
        if ($this->inscricaoMunicipal !== null) {
            $result['inscricao_municipal'] = $this->inscricaoMunicipal;
        }
        if ($this->nif !== null) {
            $result['nif'] = $this->nif;
        }
        if ($this->motivoAusenciaNif !== null) {
            $result['motivo_ausencia_nif'] = $this->motivoAusenciaNif;
        }

        return $result;
    }

    public static function fromArray(array $data): self
    {
        $endereco = EnderecoDTO::fromArray($data['endereco']);

        return new self(
            razaoSocial: $data['razaoSocial'] ?? $data['razao_social'],
            endereco: $endereco,
            cnpj: $data['cnpj'] ?? null,
            cpf: $data['cpf'] ?? null,
            email: $data['email'] ?? null,
            telefone: $data['telefone'] ?? null,
            inscricaoMunicipal: $data['inscricaoMunicipal'] ?? $data['inscricao_municipal'] ?? null,
            nif: $data['nif'] ?? null,
            motivoAusenciaNif: $data['motivoAusenciaNif'] ?? $data['motivo_ausencia_nif'] ?? null,
        );
    }
}
