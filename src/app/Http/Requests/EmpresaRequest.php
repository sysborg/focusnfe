<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $regimeTributario = array_keys(config('focusnfe.regimeTributario'));

        return [
            'razaoSocial' => 'required|string',
            'nomeFantasia' => 'required|string',
            'bairro' => 'required|string',
            'cep' => 'required|integer',
            'cnpj' => 'required|string',
            'complemento' => 'required|string',
            'email' => 'required|email',
            'inscricaoEstadual' => 'required|string',
            'inscricaoMunicipal' => 'required|string',
            'logradouro' => 'required|string',
            'numero' => 'required|integer',
            'regimeTributario' => 'required|integer|in:' . implode(',', $regimeTributario),
            'telefone' => 'required|string',
            'municipio' => 'required|string',
            'uf' => 'required|string',
            'habilitaNfe' => 'required|boolean',
            'habilitaNfce' => 'required|boolean',
            'arquivoCertificado' => 'required|string',
            'senhaCertificado' => 'required|string',
            'cscNfceProducao' => 'required|string',
            'idTokenNfceProducao' => 'required|string',
            'enviaEmailDestinatario' => 'required|boolean',
            'discriminaImposto' => 'required|boolean',
        ];
    }
}
