<?php

namespace Sysborg\FocusNfe\app\Http\Requests;


class CTERecebidasRequest extends BaseRequest
{
  
    public function rules()
    {
        return [
            'observacoes' => 'required|string|min:15|max:255',
        ];
    }
}
