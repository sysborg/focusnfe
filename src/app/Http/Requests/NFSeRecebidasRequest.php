<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Sysborg\FocusNfe\app\Rules\CnpjRule;

class NFSeRecebidasRequest extends BaseRequest
{
   

    public function rules(): array
    {
        return [
            'cnpj' => ['required', 'string', new CnpjRule()],
        ];
    }

    
}
