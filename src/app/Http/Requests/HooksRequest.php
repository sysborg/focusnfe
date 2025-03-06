<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HooksRequest extends FormRequest
{
  
    public function rules()
    {
        return [
            'cnpj_emitente' => 'required|string|min:14|max:14',
            'ref' => 'required|string',
            'status' => 'required|string',
            'status_sefaz' => 'required|string',
            'mensagem_sefaz' => 'required|string',
            'chave_nfe' => 'required|string',
            'numero' => 'required|string',
            'serie' => 'required|string',
            'caminho_xml_nota_fiscal' => 'required|string',
            'caminho_danfe' => 'required|string',
        ];
    }
}
