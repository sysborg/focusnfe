<?php

namespace Sysborg\FocusNfe\App\DTO;

use Carbon\Carbon;
use App\DTO\ServicoDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NFSeDTO extends DTO {
    public function __construct(
        public Carbon $dataEmissao,
        public PrestadorDTO $prestador,
        public TomadorDTO $tomador,
        public ServicoDTO $servico
    ) {}

    /**
     * Cria um objeto NFSeDTO a partir de um array
     * 
     * @param array $data
     * @return NFSeDTO
     */
    public static function fromArray(array $data): self
    {

        $validatedData = Validator::make($data, [
            'data_emissao' => 'required|date',
            'prestador' => 'required|array',
            'tomador' => 'required|array',
            'servico' => 'required|array'
        ])->validate(); 
    
        $prestador = PrestadorDTO::fromArray($validatedData['prestador']);
        $tomador = TomadorDTO::fromArray($validatedData['tomador']);
        $servico = ServicoDTO::fromArray($validatedData['servico']);
    
        return new self(
            $validatedData['data_emissao'],
            $prestador,
            $tomador,
            $servico
        );
    }
}
