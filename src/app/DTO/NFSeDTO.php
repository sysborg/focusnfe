<?php

namespace Sysborg\FocusNfe\app\DTO;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Sysborg\FocusNFe\app\DTO\ServicoDTO;

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

    protected function validate(): void
    {
        if ($this->dataEmissao->isFuture()) {
            throw new ValidationException(
                Validator::make([], ['dataEmissao' => 'required'], ['dataEmissao.required' => 'A data de emissão não pode ser futura'])
            );
        }
    }

    public static function rules(): array
    {
        return [
            'dataEmissao' => 'required|date',
            'prestador' => 'required|array',
            'tomador' => 'required|array',
            'servico' => 'required|array',
        ];
    }

    public static function messages(): array
    {
        return [
            'dataEmissao.required' => 'A data de emissão é obrigatória',
            'dataEmissao.date' => 'A data de emissão deve ser uma data válida',
            'prestador.required' => 'Os dados do prestador são obrigatórios',
            'prestador.array' => 'Os dados do prestador devem ser um objeto',
            'tomador.required' => 'Os dados do tomador são obrigatórios',
            'tomador.array' => 'Os dados do tomador devem ser um objeto',
            'servico.required' => 'Os dados do serviço são obrigatórios',
            'servico.array' => 'Os dados do serviço devem ser um objeto',
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            new Carbon($data['dataEmissao']),
            PrestadorDTO::fromArray($data['prestador']),
            TomadorDTO::fromArray($data['tomador']),
            ServicoDTO::fromArray($data['servico'])
        );
    }
}
