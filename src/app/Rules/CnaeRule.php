<?php

namespace Sysborg\FocusNfe\app\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Regra de validação para código CNAE
 */
class CnaeRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^\d{6}$/', $value);
    }


    /**
     * @return string
     */
    public function message()
    {
        return 'O código CNAE deve conter exatamente 6 dígitos numéricos.';
    }
}
