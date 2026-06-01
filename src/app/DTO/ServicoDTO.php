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
        public ?float $cbsValor = null,
        public ?string $codigoTributacaoNacionalIss = null,
        public ?string $codigoMunicipioPrestacao = null,
        public ?string $codigoPaisPrestacao = null,
        public ?string $codigoNcm = null,
        public ?string $ibsCbsClassificacaoTributariaRegular = null,
        public ?string $ibsCbsSituacaoTributariaRegular = null,
        public ?string $ibsCbsCreditoCodigoClassificacao = null,
        public ?float $ibsMunPercentualDiferimento = null,
        public ?float $ibsUfPercentualDiferimento = null,
        public ?string $situacaoTributariaPisCofins = null,
        public ?string $tipoRetencaoPisCofins = null,
        public ?string $codigoAnexoCnae = null,
        public ?float $valorServicoExt = null,
        public ?float $valorFinalCobrado = null,
        public ?float $valorInicialCobrado = null,
        public ?float $valorIpi = null,
        public ?float $valorJuros = null,
        public ?float $valorMulta = null,
        public ?bool $pisRetido = null,
        public ?bool $cofinsRetido = null,
        public ?bool $csllRetido = null,
        public ?bool $inssRetido = null,
        public ?bool $irRetido = null,
        public ?bool $icmsRetido = null,
        public ?bool $cpRetido = null,
        public ?float $aliquotaPis = null,
        public ?float $aliquotaCofins = null,
        public ?float $aliquotaCsll = null,
        public ?float $aliquotaInss = null,
        public ?float $aliquotaIr = null,
        public ?float $aliquotaIcms = null,
        public ?float $aliquotaCp = null,
        public array $camposExtras = [],
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
            'codigoTributacaoNacionalIss' => 'nullable|string',
            'codigoMunicipioPrestacao' => 'nullable|string',
            'codigoPaisPrestacao' => 'nullable|string',
            'codigoNcm' => 'nullable|string',
            'ibsCbsClassificacaoTributariaRegular' => 'nullable|string',
            'ibsCbsSituacaoTributariaRegular' => 'nullable|string',
            'ibsCbsCreditoCodigoClassificacao' => 'nullable|string',
            'ibsMunPercentualDiferimento' => 'nullable|numeric|min:0',
            'ibsUfPercentualDiferimento' => 'nullable|numeric|min:0',
            'situacaoTributariaPisCofins' => 'nullable|string',
            'tipoRetencaoPisCofins' => 'nullable|string',
            'codigoAnexoCnae' => 'nullable|string',
            'valorServicoExt' => 'nullable|numeric|min:0',
            'valorFinalCobrado' => 'nullable|numeric|min:0',
            'valorInicialCobrado' => 'nullable|numeric|min:0',
            'valorIpi' => 'nullable|numeric|min:0',
            'valorJuros' => 'nullable|numeric|min:0',
            'valorMulta' => 'nullable|numeric|min:0',
            'pisRetido' => 'nullable|boolean',
            'cofinsRetido' => 'nullable|boolean',
            'csllRetido' => 'nullable|boolean',
            'inssRetido' => 'nullable|boolean',
            'irRetido' => 'nullable|boolean',
            'icmsRetido' => 'nullable|boolean',
            'cpRetido' => 'nullable|boolean',
            'aliquotaPis' => 'nullable|numeric|min:0',
            'aliquotaCofins' => 'nullable|numeric|min:0',
            'aliquotaCsll' => 'nullable|numeric|min:0',
            'aliquotaInss' => 'nullable|numeric|min:0',
            'aliquotaIr' => 'nullable|numeric|min:0',
            'aliquotaIcms' => 'nullable|numeric|min:0',
            'aliquotaCp' => 'nullable|numeric|min:0',
            'camposExtras' => 'array',
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
            self::numericValue($data, 'cbsValor', 'cbs_valor'),
            self::value($data, 'codigoTributacaoNacionalIss', 'codigo_tributacao_nacional_iss'),
            self::value($data, 'codigoMunicipioPrestacao', 'codigo_municipio_prestacao'),
            self::value($data, 'codigoPaisPrestacao', 'codigo_pais_prestacao'),
            self::value($data, 'codigoNcm', 'codigo_ncm'),
            self::value($data, 'ibsCbsClassificacaoTributariaRegular', 'ibs_cbs_classificacao_tributaria_regular'),
            self::value($data, 'ibsCbsSituacaoTributariaRegular', 'ibs_cbs_situacao_tributaria_regular'),
            self::value($data, 'ibsCbsCreditoCodigoClassificacao', 'ibs_cbs_credito_codigo_classificacao'),
            self::numericValue($data, 'ibsMunPercentualDiferimento', 'ibs_mun_percentual_diferimento'),
            self::numericValue($data, 'ibsUfPercentualDiferimento', 'ibs_uf_percentual_diferimento'),
            self::value($data, 'situacaoTributariaPisCofins', 'situacao_tributaria_pis_cofins'),
            self::value($data, 'tipoRetencaoPisCofins', 'tipo_retencao_pis_cofins'),
            self::value($data, 'codigoAnexoCnae', 'codigo_anexo_cnae'),
            self::numericValue($data, 'valorServicoExt', 'valor_servico_ext'),
            self::numericValue($data, 'valorFinalCobrado', 'valor_final_cobrado'),
            self::numericValue($data, 'valorInicialCobrado', 'valor_inicial_cobrado'),
            self::numericValue($data, 'valorIpi', 'valor_ipi'),
            self::numericValue($data, 'valorJuros', 'valor_juros'),
            self::numericValue($data, 'valorMulta', 'valor_multa'),
            self::boolValue($data, 'pisRetido', 'pis_retido'),
            self::boolValue($data, 'cofinsRetido', 'cofins_retido'),
            self::boolValue($data, 'csllRetido', 'csll_retido'),
            self::boolValue($data, 'inssRetido', 'inss_retido'),
            self::boolValue($data, 'irRetido', 'ir_retido'),
            self::boolValue($data, 'icmsRetido', 'icms_retido'),
            self::boolValue($data, 'cpRetido', 'cp_retido'),
            self::numericValue($data, 'aliquotaPis', 'aliquota_pis'),
            self::numericValue($data, 'aliquotaCofins', 'aliquota_cofins'),
            self::numericValue($data, 'aliquotaCsll', 'aliquota_csll'),
            self::numericValue($data, 'aliquotaInss', 'aliquota_inss'),
            self::numericValue($data, 'aliquotaIr', 'aliquota_ir'),
            self::numericValue($data, 'aliquotaIcms', 'aliquota_icms'),
            self::numericValue($data, 'aliquotaCp', 'aliquota_cp'),
            self::value($data, 'camposExtras', 'campos_extras') ?? [],
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

    private static function boolValue(array $data, string $camelKey, ?string $snakeKey = null): ?bool
    {
        $value = self::value($data, $camelKey, $snakeKey);
        return $value === null ? null : (bool) $value;
    }

    /**
     * Converte o serviço para array sem enviar campos opcionais nulos.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = parent::toArray();
        $camposExtras = $payload['campos_extras'] ?? [];

        unset($payload['campos_extras']);

        $payload = array_filter($payload, static fn (mixed $value): bool => $value !== null);

        return array_merge($payload, $camposExtras);
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
