<?php

namespace Sysborg\FocusNFe\App\Http\Requests;

use Sysborg\FocusNFe\App\Rules\CnpjRule;
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
