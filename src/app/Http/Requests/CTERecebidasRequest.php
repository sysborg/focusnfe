<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CTERecebidasRequest extends FormRequest
{
  
    public function rules()
    {
        return [
            'observacoes' => 'required|string|min:15|max:255',
        ];
    }
}
