<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ServicoDTO extends DTO
{
    public function __construct(
        public float $aliquota,
        public string $discriminacao,
        public bool $issRetido,
        public string $itemListaServico,
        public string $codigoTributarioMunicipio,
        public float $valorServicos,
        public ?string $codigoCnae = null,
        // Campos de deduções e retenções (NFSe municipal)
        public ?float $valorDeducoes = null,
        public ?float $valorPis = null,
        public ?float $valorCofins = null,
        public ?float $valorInss = null,
        public ?float $valorIr = null,
        public ?float $valorCsll = null,
        public ?float $valorIss = null,
        public ?float $valorIssRetido = null,
        public ?float $outrasRetencoes = null,
        public ?float $baseCalculo = null,
        public ?float $descontoIncondicionado = null,
        public ?float $descontoCondicionado = null,
        public ?float $percentualTotalTributos = null,
        public ?string $fonteTotalTributos = null,
        public ?string $codigoMunicipio = null,
        // Reforma Tributária — IBS/CBS
        public ?string $codigoNbs = null,
        public ?string $codigoIndicadorOperacao = null,
        public ?string $ibsCbsClassificacaoTributaria = null,
        public ?string $ibsCbsSituacaoTributaria = null,
        public ?float $ibsCbsBaseCalculo = null,
        public ?float $ibsUfAliquota = null,
        public ?float $ibsMunAliquota = null,
        public ?float $cbsAliquota = null,
        public ?float $ibsUfValor = null,
        public ?float $ibsMunValor = null,
        public ?float $cbsValor = null
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
        $validator = Validator::make($this->validationData(), self::rules(), self::messages());

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
            'valorDeducoes' => 'nullable|numeric|min:0',
            'valorPis' => 'nullable|numeric|min:0',
            'valorCofins' => 'nullable|numeric|min:0',
            'valorInss' => 'nullable|numeric|min:0',
            'valorIr' => 'nullable|numeric|min:0',
            'valorCsll' => 'nullable|numeric|min:0',
            'valorIss' => 'nullable|numeric|min:0',
            'valorIssRetido' => 'nullable|numeric|min:0',
            'outrasRetencoes' => 'nullable|numeric|min:0',
            'baseCalculo' => 'nullable|numeric|min:0',
            'descontoIncondicionado' => 'nullable|numeric|min:0',
            'descontoCondicionado' => 'nullable|numeric|min:0',
            'percentualTotalTributos' => 'nullable|numeric|min:0',
            'fonteTotalTributos' => 'nullable|string',
            'codigoMunicipio' => 'nullable|string|max:7',
            'codigoNbs' => 'nullable|string',
            'codigoIndicadorOperacao' => 'nullable|string',
            'ibsCbsClassificacaoTributaria' => 'nullable|string',
            'ibsCbsSituacaoTributaria' => 'nullable|string',
            'ibsCbsBaseCalculo' => 'nullable|numeric|min:0',
            'ibsUfAliquota' => 'nullable|numeric|min:0',
            'ibsMunAliquota' => 'nullable|numeric|min:0',
            'cbsAliquota' => 'nullable|numeric|min:0',
            'ibsUfValor' => 'nullable|numeric|min:0',
            'ibsMunValor' => 'nullable|numeric|min:0',
            'cbsValor' => 'nullable|numeric|min:0',
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
            'codigoNbs.string' => 'O código NBS deve ser um texto',
            'codigoIndicadorOperacao.string' => 'O indicador de operação deve ser um texto',
            'ibsCbsClassificacaoTributaria.string' => 'A classificação tributária IBS/CBS deve ser um texto',
            'ibsCbsSituacaoTributaria.string' => 'A situação tributária IBS/CBS deve ser um texto',
            'ibsCbsBaseCalculo.numeric' => 'A base de cálculo IBS/CBS deve ser numérica',
            'ibsUfAliquota.numeric' => 'A alíquota IBS da UF deve ser numérica',
            'ibsMunAliquota.numeric' => 'A alíquota IBS do município deve ser numérica',
            'cbsAliquota.numeric' => 'A alíquota CBS deve ser numérica',
            'ibsUfValor.numeric' => 'O valor IBS da UF deve ser numérico',
            'ibsMunValor.numeric' => 'O valor IBS do município deve ser numérico',
            'cbsValor.numeric' => 'O valor CBS deve ser numérico',
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
            self::value($data, 'aliquota'),
            self::value($data, 'discriminacao'),
            self::value($data, 'issRetido', 'iss_retido'),
            self::value($data, 'itemListaServico', 'item_lista_servico'),
            self::value($data, 'codigoTributarioMunicipio', 'codigo_tributario_municipio'),
            self::value($data, 'valorServicos', 'valor_servicos'),
            self::value($data, 'codigoCnae', 'codigo_cnae'),
            self::numericValue($data, 'valorDeducoes', 'valor_deducoes'),
            self::numericValue($data, 'valorPis', 'valor_pis'),
            self::numericValue($data, 'valorCofins', 'valor_cofins'),
            self::numericValue($data, 'valorInss', 'valor_inss'),
            self::numericValue($data, 'valorIr', 'valor_ir'),
            self::numericValue($data, 'valorCsll', 'valor_csll'),
            self::numericValue($data, 'valorIss', 'valor_iss'),
            self::numericValue($data, 'valorIssRetido', 'valor_iss_retido'),
            self::numericValue($data, 'outrasRetencoes', 'outras_retencoes'),
            self::numericValue($data, 'baseCalculo', 'base_calculo'),
            self::numericValue($data, 'descontoIncondicionado', 'desconto_incondicionado'),
            self::numericValue($data, 'descontoCondicionado', 'desconto_condicionado'),
            self::numericValue($data, 'percentualTotalTributos', 'percentual_total_tributos'),
            self::value($data, 'fonteTotalTributos', 'fonte_total_tributos'),
            self::value($data, 'codigoMunicipio', 'codigo_municipio'),
            self::value($data, 'codigoNbs', 'codigo_nbs'),
            self::value($data, 'codigoIndicadorOperacao', 'codigo_indicador_operacao'),
            self::value($data, 'ibsCbsClassificacaoTributaria', 'ibs_cbs_classificacao_tributaria'),
            self::value($data, 'ibsCbsSituacaoTributaria', 'ibs_cbs_situacao_tributaria'),
            self::numericValue($data, 'ibsCbsBaseCalculo', 'ibs_cbs_base_calculo'),
            self::numericValue($data, 'ibsUfAliquota', 'ibs_uf_aliquota'),
            self::numericValue($data, 'ibsMunAliquota', 'ibs_mun_aliquota'),
            self::numericValue($data, 'cbsAliquota', 'cbs_aliquota'),
            self::numericValue($data, 'ibsUfValor', 'ibs_uf_valor'),
            self::numericValue($data, 'ibsMunValor', 'ibs_mun_valor'),
            self::numericValue($data, 'cbsValor', 'cbs_valor')
        );
    }

    private static function value(array $data, string $camelKey, ?string $snakeKey = null): mixed
    {
        return $data[$camelKey] ?? ($snakeKey !== null ? ($data[$snakeKey] ?? null) : null);
    }

    private static function numericValue(array $data, string $camelKey, ?string $snakeKey = null): ?float
    {
        $value = self::value($data, $camelKey, $snakeKey);
        return $value === null ? null : (float) $value;
    }

    /**
     * Evita deprecations do Brick\Math ao validar floats no Laravel.
     *
     * @return array<string, mixed>
     */
    private function validationData(): array
    {
        return array_map(
            static fn (mixed $value): mixed => is_float($value) ? (string) $value : $value,
            get_object_vars($this)
        );
    }
}
