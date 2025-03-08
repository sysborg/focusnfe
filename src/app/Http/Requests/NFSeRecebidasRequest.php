<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Sysborg\FocusNFe\app\Rules\CnpjRule;
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
