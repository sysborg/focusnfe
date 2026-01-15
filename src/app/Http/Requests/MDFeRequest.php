<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MDFeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'emitente' => 'required|integer|in:1,2,3', 
            'serie' => 'required|integer|between:1,3', 
            'numero' => 'required|integer|between:1,9', 
            'uf_inicio' => 'required|string|size:2', 
            'uf_fim' => 'required|string|size:2', 
            'cnpj_emitente' => 'required|string|size:14', 
            'cpf_emitente' => 'nullable|string|size:11', 
            'inscricao_estadual_emitente' => 'required|string|size:14', 
            'nome_emitente' => 'required|string|min:2|max:60', 
            'nome_fantasia_emitente' => 'nullable|string|min:2|max:60', 
            'logradouro_emitente' => 'required|string|min:2|max:60', 
            'numero_emitente' => 'required|string|min:1|max:60', 
            'bairro_emitente' => 'required|string|min:2|max:60', 
            'codigo_municipio_emitente' => 'required|integer|digits:7', 
            'municipio_emitente' => 'required|string|min:2|max:60', 
            'uf_emitente' => 'required|string|size:2', 
            'valor_total_carga' => 'required|numeric|min:0|max:999999999.99', 
            'codigo_unidade_medida_peso_bruto' => 'required|integer|in:1,2', 
        ];
    }
}
