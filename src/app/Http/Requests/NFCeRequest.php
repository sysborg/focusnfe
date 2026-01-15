<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFCeRequest extends FormRequest
{
    
    public function rules()
    {
        return [
            'natureza_operacao' => 'required|string',
            'data_emissao' => 'required|date',
            'presenca_comprador' => 'required|integer',
            'cnpj_emitente' => 'required|string',
            'modalidade_frete' => 'required|integer',
            'local_destino' => 'required|integer',
        ];
    }
}