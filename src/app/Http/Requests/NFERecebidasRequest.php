<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFeRecebidasRequest extends FormRequest
{
  
    public function rules()
    {
        return [
            'tipo' => 'required|string',
            'justificativa' => 'nullable|string',
        ];
    }
}