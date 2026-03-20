<?php

namespace Sysborg\FocusNfe\app\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Regra de validação para CEP
 */
class CepRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cep = preg_replace('/\D/', '', $value);

        if (strlen($cep) !== 8) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'O CEP informado é inválido!';
    }
}
