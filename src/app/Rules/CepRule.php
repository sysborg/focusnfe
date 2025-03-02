<?php

namespace Sysborg\FocusNFe\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CepRule implements Rule
{
    public function passes($attribute, $value)
    {
        $cep = preg_replace('/\D/', '', $value);

        if (strlen($cep) !== 8) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'O CEP informado é inválido!';
    }
}
