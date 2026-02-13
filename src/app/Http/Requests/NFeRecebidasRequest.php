<?php

namespace Sysborg\FocusNfe\app\Http\Requests;


class NFeRecebidasRequest extends BaseRequest
{
  
    public function rules()
    {
        return [
            'tipo' => 'required|string',
            'justificativa' => 'nullable|string',
        ];
    }
}