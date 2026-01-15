<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFSeNacionalRequest extends FormRequest
{

    
    public function rules()
    {
        return [
            'data_emissao' => 'required|date',
            'data_competencia' => 'required|date',

            'codigo_municipio_emissora' => 'required|string',
            
            'prestador' => 'required|array',
            'prestador.cnpj' => 'required|string',
            'prestador.inscricao_municipal' => 'required|string',
            'prestador.codigo_opcao_simples_nacional' => 'required|integer',
            'prestador.regime_especial_tributacao' => 'required|integer',

            'tomador' => 'required|array',
            'tomador.cnpj' => 'required|string',
            'tomador.razao_social' => 'required|string',
            'tomador.email' => 'required|email',
            'tomador.telefone' => 'required|string',
            'tomador.endereco' => 'required|array',
            'tomador.endereco.cep' => 'required|string',
            'tomador.endereco.logradouro' => 'required|string',
            'tomador.endereco.numero' => 'required|string',
            'tomador.endereco.complemento' => 'nullable|string',
            'tomador.endereco.bairro' => 'required|string',
            'tomador.endereco.codigo_municipio' => 'required|string',

            'servico' => 'required|array',
            'servico.codigo_tributacao_nacional_iss' => 'required|string',
            'servico.descricao_servico' => 'required|string',
            'servico.valor_servico' => 'required|numeric',
            'servico.tributacao_iss' => 'required|integer',
            'servico.tipo_retencao_iss' => 'required|integer',
        ];
    }
}
