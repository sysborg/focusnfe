<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFSeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dataEmissao' => 'required|date',
            'prestador' => 'required|array',
            'prestador.cnpj' => 'required|string',
            'prestador.inscricao_municipal' => 'required|string',
            'prestador.codigo_municipio' => 'required|string',
            'tomador' => 'required|array',
            'tomador.cnpj' => 'required|string',
            'tomador.razao_social' => 'required|string',
            'tomador.email' => 'required|email',
            'tomador.endereco' => 'required|array',
            'tomador.endereco.logradouro' => 'required|string',
            'tomador.endereco.numero' => 'required|string',
            'tomador.endereco.complemento' => 'string',
            'tomador.endereco.bairro' => 'required|string',
            'tomador.endereco.codigo_municipio' => 'required|string',
            'tomador.endereco.uf' => 'required|string',
            'tomador.endereco.cep' => 'required|string',
            'servico' => 'required|array',
            'servico.aliquota' => 'required|numeric',
            'servico.discriminacao' => 'required|string',
            'servico.iss_retido' => 'required|boolean',
            'servico.item_lista_servico' => 'required|string',
            'servico.codigo_tributario_municipio' => 'required|string',
            'servico.valor_servicos' => 'required|numeric',
            'servico.codigo_cnae' => 'nullable|string',
        ];
    }
}
