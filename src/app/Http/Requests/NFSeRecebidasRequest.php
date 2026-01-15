<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Sysborg\FocusNfe\app\Rules\CnpjRule;
use Illuminate\Foundation\Http\FormRequest;

class NFSeRecebidasRequest extends FormRequest
{
   

    public function rules(): array
    {
        return [
            'cnpj' => ['required', 'string', new CnpjRule()],
        ];
    }

    
}
