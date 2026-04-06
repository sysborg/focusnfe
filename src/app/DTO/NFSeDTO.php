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
        public ?string $numeroRpsSubstituido = null,
        public ?string $serieRpsSubstituido = null,
        public ?string $tipoRpsSubstituido = null,
        public ?array $intermediario = null,
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
            'numeroRpsSubstituido' => 'nullable|string',
            'serieRpsSubstituido' => 'nullable|string',
            'tipoRpsSubstituido' => 'nullable|string|max:1',
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
            self::value($data, 'numeroRpsSubstituido', 'numero_rps_substituido'),
            self::value($data, 'serieRpsSubstituido', 'serie_rps_substituido'),
            self::value($data, 'tipoRpsSubstituido', 'tipo_rps_substituido'),
            $data['intermediario'] ?? null,
        );
    }

    private static function value(array $data, string $camelKey, ?string $snakeKey = null): mixed
    {
        return $data[$camelKey] ?? ($snakeKey !== null ? ($data[$snakeKey] ?? null) : null);
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
        if ($this->numeroRpsSubstituido !== null) {
            $result['numero_rps_substituido'] = $this->numeroRpsSubstituido;
            $result['serie_rps_substituido'] = $this->serieRpsSubstituido;
            $result['tipo_rps_substituido'] = $this->tipoRpsSubstituido;
        }
        if ($this->intermediario !== null) {
            $result['intermediario'] = $this->intermediario;
        }

        return $result;
    }
}
