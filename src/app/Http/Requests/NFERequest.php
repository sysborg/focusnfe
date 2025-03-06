<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFeRequest extends FormRequest
{
  
    public function rules()
    {
        return [
            'natureza_operacao' => 'required|string',
            'data_emissao' => 'required|date',
            'tipo_documento' => 'required|integer',
            'local_destino' => 'required|integer|in:1,2,3',
            'finalidade_emissao' => 'required|integer',
            'consumidor_final' => 'required|integer|',
            'presenca_comprador' => 'required|integer',
            
            'cnpj_emitente' => 'required_without:cpf_emitente|string',
            'cpf_emitente' => 'required_without:cnpj_emitente|string',
            'inscricao_estadual_emitente' => 'required|string',
            'logradouro_emitente' => 'required|string',
            'numero_emitente' => 'required|string',
            'bairro_emitente' => 'required|string',
            'municipio_emitente' => 'required|string',
            'uf_emitente' => 'required|string',
            'regime_tributario_emitente' => 'required|integer',
            
            'nome_destinatario' => 'required|string',
            'cnpj_destinatario' => 'nullable|string',
            'cpf_destinatario' => 'nullable|string',
            'inscricao_estadual_destinatario' => 'nullable',
            'logradouro_destinatario' => 'required|string',
            'numero_destinatario' => 'required|string',
            'bairro_destinatario' => 'required|string',
            'municipio_destinatario' => 'required|string',
            'uf_destinatario' => 'required|string',
            'indicador_inscricao_estadual_destinatario' => 'required|integer',
            
            'itens' => 'required|array',
        ];
    }
}