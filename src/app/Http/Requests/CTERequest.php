<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CTeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cnpj_emitente' => 'required|string',
            'tomador' => 'required|string',
            
            'modal' => [
                'required',
                'string',
                Rule::in([
                    'modal_rodoviario', 
                    'modal_aereo', 
                    'modal_aquaviario', 
                    'modal_ferroviario', 
                    'modal_dutoviario', 
                    'modal_multimodal'
                ])
            ],

            'dados_transporte' => 'required|array',
            'dados_transporte.tipo' => 'required|string',
            'dados_transporte.veiculo' => 'required|array',
            'dados_transporte.rota' => 'required|array',

            'valores' => 'required|array',
            'valores.valor_frete' => 'required|numeric',
            'valores.valor_seguro' => 'nullable|numeric',

            
        ];
    }
}
