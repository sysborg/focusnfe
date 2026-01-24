<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ServicoDTO extends DTO
{
    /**
     * Special cases
     * 
     * @var array
     */
    protected array $specialCases = [
        'issRetido' => function ($value) {
            return $value ? 'true' : 'false';
        }
    ];

    public function __construct(
        public float $aliquota,
        public string $discriminacao,
        public bool $issRetido,
        public string $itemListaServico,
        public string $codigoTributarioMunicipio,
        public float $valorServicos,
        public ?string $codigoCnae = null
    ) {
        $this->validate();
    }

    /**
     * Valida os dados do ServicoDTO
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
            'aliquota' => 'required|numeric|min:0|max:100',
            'discriminacao' => 'required|string',
            'issRetido' => 'required|boolean',
            'itemListaServico' => 'required|string|max:10',
            'codigoTributarioMunicipio' => 'required|string|max:20',
            'valorServicos' => 'required|numeric|min:0.01',
            'codigoCnae' => 'nullable|string|max:10',
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
            'aliquota.required' => 'A alíquota é obrigatória',
            'aliquota.numeric' => 'A alíquota deve ser um número',
            'aliquota.min' => 'A alíquota deve ser maior ou igual a 0',
            'aliquota.max' => 'A alíquota deve ser menor ou igual a 100',
            'discriminacao.required' => 'A discriminação do serviço é obrigatória',
            'discriminacao.string' => 'A discriminação do serviço deve ser um texto',
            'issRetido.required' => 'O campo ISS retido é obrigatório',
            'issRetido.boolean' => 'O campo ISS retido deve ser verdadeiro ou falso',
            'itemListaServico.required' => 'O item da lista de serviço é obrigatório',
            'itemListaServico.string' => 'O item da lista de serviço deve ser um texto',
            'itemListaServico.max' => 'O item da lista de serviço não pode ter mais de 10 caracteres',
            'codigoTributarioMunicipio.required' => 'O código tributário do município é obrigatório',
            'codigoTributarioMunicipio.string' => 'O código tributário do município deve ser um texto',
            'codigoTributarioMunicipio.max' => 'O código tributário do município não pode ter mais de 20 caracteres',
            'valorServicos.required' => 'O valor dos serviços é obrigatório',
            'valorServicos.numeric' => 'O valor dos serviços deve ser um número',
            'valorServicos.min' => 'O valor dos serviços deve ser maior que zero',
            'codigoCnae.string' => 'O código CNAE deve ser um texto',
            'codigoCnae.max' => 'O código CNAE não pode ter mais de 10 caracteres',
        ];
    }

    /**
     * Cria uma instância da classe ServicoDTO a partir de um array
     *
     * @param array $data Array com os dados em camelCase
     * @return ServicoDTO
     */
    public static function fromArray(array $data): ServicoDTO
    {
        return new ServicoDTO(
            $data['aliquota'],
            $data['discriminacao'],
            $data['issRetido'],
            $data['itemListaServico'],
            $data['codigoTributarioMunicipio'],
            $data['valorServicos'],
            $data['codigoCnae'] ?? null
        );
    }
}
