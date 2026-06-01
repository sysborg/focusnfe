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
        public ServicoDTO $servico,
        public bool $optanteSimplesNacional = true,
        // Campos opcionais documentados pela API
        public ?string $naturezaOperacao = null,
        public ?string $regimeEspecialTributacao = null,
        public bool $incentivadorCultural = false,
        public ?string $codigoObra = null,
        public ?string $art = null,
        public ?string $numeroRps = null,
        public ?string $serieRps = null,
        public ?string $tipoRps = null,
        public ?Carbon $dataEmissaoRps = null,
        public ?string $numeroNfseSubstituido = null,
        public ?string $numeroRpsSubstituido = null,
        public ?string $serieRpsSubstituido = null,
        public ?string $tipoRpsSubstituido = null,
        public ?array $intermediario = null,
        // Campos de Reforma Tributária e exceções por município
        public ?string $codigoIndicadorOperacao = null,
        public ?string $codigoMoeda = null,
        public ?string $codigoOpcaoSimplesNacional = null,
        public ?string $codigoPaisResultado = null,
        public ?string $consumidorFinal = null,
        public ?Carbon $dataCompetencia = null,
        public ?Carbon $dataEmissaoDps = null,
        public ?string $regimeTributarioSimplesNacional = null,
        public ?string $indicadorDestinatario = null,
        public ?string $pagamentoParceladoAntecipado = null,
        public ?string $tipoExigibilidadeSuspensao = null,
        public ?float $valorRepasse = null,
        public ?string $modoPrestacao = null,
        public ?string $vinculoNegocio = null,
        public ?array $imovel = null,
        public ?array $obra = null,
        public array $camposExtras = [],
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
            'optanteSimplesNacional' => 'required|boolean',
            'naturezaOperacao' => 'nullable|string|max:2',
            'regimeEspecialTributacao' => 'nullable|string|max:1',
            'incentivadorCultural' => 'boolean',
            'codigoObra' => 'nullable|string|max:15',
            'art' => 'nullable|string|max:30',
            'numeroRps' => 'nullable|string',
            'serieRps' => 'nullable|string',
            'tipoRps' => 'nullable|string|max:1',
            'dataEmissaoRps' => 'nullable|date',
            'numeroNfseSubstituido' => 'nullable|string',
            'numeroRpsSubstituido' => 'nullable|string',
            'serieRpsSubstituido' => 'nullable|string',
            'tipoRpsSubstituido' => 'nullable|string|max:1',
            'codigoIndicadorOperacao' => 'nullable|string',
            'codigoMoeda' => 'nullable|string',
            'codigoOpcaoSimplesNacional' => 'nullable|string',
            'codigoPaisResultado' => 'nullable|string',
            'consumidorFinal' => 'nullable|string',
            'dataCompetencia' => 'nullable|date',
            'dataEmissaoDps' => 'nullable|date',
            'regimeTributarioSimplesNacional' => 'nullable|string',
            'indicadorDestinatario' => 'nullable|string',
            'pagamentoParceladoAntecipado' => 'nullable|string',
            'tipoExigibilidadeSuspensao' => 'nullable|string',
            'valorRepasse' => 'nullable|numeric|min:0',
            'modoPrestacao' => 'nullable|string',
            'vinculoNegocio' => 'nullable|string',
            'imovel' => 'nullable|array',
            'obra' => 'nullable|array',
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
            'dataEmissao.required' => 'A data de emissão é obrigatória',
            'dataEmissao.date' => 'A data de emissão deve ser uma data válida',
            'optanteSimplesNacional.required' => 'O campo optante pelo Simples Nacional é obrigatório',
            'optanteSimplesNacional.boolean' => 'O campo optante pelo Simples Nacional deve ser verdadeiro ou falso',
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
        $dataEmissao = self::value($data, 'dataEmissao', 'data_emissao');

        return new self(
            $dataEmissao instanceof Carbon ? $dataEmissao : new Carbon($dataEmissao),
            PrestadorDTO::fromArray($data['prestador']),
            TomadorDTO::fromArray($data['tomador']),
            ServicoDTO::fromArray($data['servico']),
            self::value($data, 'optanteSimplesNacional', 'optante_simples_nacional') ?? true,
            self::value($data, 'naturezaOperacao', 'natureza_operacao'),
            self::value($data, 'regimeEspecialTributacao', 'regime_especial_tributacao'),
            (bool) (self::value($data, 'incentivadorCultural', 'incentivador_cultural') ?? false),
            self::value($data, 'codigoObra', 'codigo_obra'),
            $data['art'] ?? null,
            self::value($data, 'numeroRps', 'numero_rps'),
            self::value($data, 'serieRps', 'serie_rps'),
            self::value($data, 'tipoRps', 'tipo_rps'),
            self::carbonValue($data, 'dataEmissaoRps', 'data_emissao_rps'),
            self::value($data, 'numeroNfseSubstituido', 'numero_nfse_substituido'),
            self::value($data, 'numeroRpsSubstituido', 'numero_rps_substituido'),
            self::value($data, 'serieRpsSubstituido', 'serie_rps_substituido'),
            self::value($data, 'tipoRpsSubstituido', 'tipo_rps_substituido'),
            $data['intermediario'] ?? null,
            self::value($data, 'codigoIndicadorOperacao', 'codigo_indicador_operacao'),
            self::value($data, 'codigoMoeda', 'codigo_moeda'),
            self::value($data, 'codigoOpcaoSimplesNacional', 'codigo_opcao_simples_nacional'),
            self::value($data, 'codigoPaisResultado', 'codigo_pais_resultado'),
            self::value($data, 'consumidorFinal', 'consumidor_final'),
            self::carbonValue($data, 'dataCompetencia', 'data_competencia'),
            self::carbonValue($data, 'dataEmissaoDps', 'data_emissao_dps'),
            self::value($data, 'regimeTributarioSimplesNacional', 'regime_tributario_simples_nacional'),
            self::value($data, 'indicadorDestinatario', 'indicador_destinatario'),
            self::value($data, 'pagamentoParceladoAntecipado', 'pagamento_parcelado_antecipado'),
            self::value($data, 'tipoExigibilidadeSuspensao', 'tipo_exigibilidade_suspensao'),
            self::numericValue($data, 'valorRepasse', 'valor_repasse'),
            self::value($data, 'modoPrestacao', 'modo_prestacao'),
            self::value($data, 'vinculoNegocio', 'vinculo_negocio'),
            $data['imovel'] ?? null,
            $data['obra'] ?? null,
            self::value($data, 'camposExtras', 'campos_extras') ?? [],
        );
    }

    private static function value(array $data, string $camelKey, ?string $snakeKey = null): mixed
    {
        return $data[$camelKey] ?? ($snakeKey !== null ? ($data[$snakeKey] ?? null) : null);
    }

    private static function carbonValue(array $data, string $camelKey, ?string $snakeKey = null): ?Carbon
    {
        $value = self::value($data, $camelKey, $snakeKey);

        if ($value === null || $value instanceof Carbon) {
            return $value;
        }

        return new Carbon($value);
    }

    private static function numericValue(array $data, string $camelKey, ?string $snakeKey = null): ?float
    {
        $value = self::value($data, $camelKey, $snakeKey);

        return $value === null ? null : (float) $value;
    }

    /**
     * Converte o DTO para array aninhado em snake_case para enviar à API
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'data_emissao' => $this->dataEmissao->format('Y-m-d'),
            'prestador' => $this->prestador->toArray(),
            'optante_simples_nacional' => $this->optanteSimplesNacional,
            'tomador' => $this->tomador->toArray(),
            'servico' => $this->servico->toArray(),
        ];

        if ($this->naturezaOperacao !== null) {
            $result['natureza_operacao'] = $this->naturezaOperacao;
        }
        if ($this->regimeEspecialTributacao !== null) {
            $result['regime_especial_tributacao'] = $this->regimeEspecialTributacao;
        }
        if ($this->incentivadorCultural) {
            $result['incentivador_cultural'] = $this->incentivadorCultural;
        }
        if ($this->codigoObra !== null) {
            $result['codigo_obra'] = $this->codigoObra;
        }
        if ($this->art !== null) {
            $result['art'] = $this->art;
        }
        if ($this->numeroRps !== null) {
            $result['numero_rps'] = $this->numeroRps;
        }
        if ($this->serieRps !== null) {
            $result['serie_rps'] = $this->serieRps;
        }
        if ($this->tipoRps !== null) {
            $result['tipo_rps'] = $this->tipoRps;
        }
        if ($this->dataEmissaoRps !== null) {
            $result['data_emissao_rps'] = $this->dataEmissaoRps->toIso8601String();
        }
        if ($this->numeroNfseSubstituido !== null) {
            $result['numero_nfse_substituido'] = $this->numeroNfseSubstituido;
        }
        if ($this->numeroRpsSubstituido !== null) {
            $result['numero_rps_substituido'] = $this->numeroRpsSubstituido;
            $result['serie_rps_substituido'] = $this->serieRpsSubstituido;
            $result['tipo_rps_substituido'] = $this->tipoRpsSubstituido;
        }
        if ($this->intermediario !== null) {
            $result['intermediario'] = $this->intermediario;
        }

        return array_merge($result, $this->reformaTributariaPayload(), $this->camposExtras);
    }

    /**
     * Campos de Reforma Tributária variam por município/provedor.
     *
     * @return array<string, mixed>
     */
    private function reformaTributariaPayload(): array
    {
        $fields = [
            'codigo_indicador_operacao' => $this->codigoIndicadorOperacao,
            'codigo_moeda' => $this->codigoMoeda,
            'codigo_opcao_simples_nacional' => $this->codigoOpcaoSimplesNacional,
            'codigo_pais_resultado' => $this->codigoPaisResultado,
            'consumidor_final' => $this->consumidorFinal,
            'data_competencia' => $this->dataCompetencia?->format('Y-m-d'),
            'data_emissao_dps' => $this->dataEmissaoDps?->toIso8601String(),
            'regime_tributario_simples_nacional' => $this->regimeTributarioSimplesNacional,
            'indicador_destinatario' => $this->indicadorDestinatario,
            'pagamento_parcelado_antecipado' => $this->pagamentoParceladoAntecipado,
            'tipo_exigibilidade_suspensao' => $this->tipoExigibilidadeSuspensao,
            'valor_repasse' => $this->valorRepasse,
            'modo_prestacao' => $this->modoPrestacao,
            'vinculo_negocio' => $this->vinculoNegocio,
            'imovel' => $this->imovel,
            'obra' => $this->obra,
        ];

        return array_filter($fields, static fn (mixed $value): bool => $value !== null);
    }
}
