<?php

namespace Sysborg\FocusNfe\app\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Regra de validação para CNPJ
 */
class CnpjRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cnpj = preg_replace('/\D/', '', $value);

        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        for ($t = 12; $t < 14; $t++) {
            $soma = 0;
            $multiplicador = $t - 7;

            for ($i = 0; $i < $t; $i++) {
                $soma += $cnpj[$i] * $multiplicador--;
                if ($multiplicador < 2) {
                    $multiplicador = 9;
                }
            }

            $digitoVerificador = ($soma * 10) % 11;
            if ($digitoVerificador === 10) {
                $digitoVerificador = 0;
            }

            if ($cnpj[$t] != $digitoVerificador) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'O CNPJ informado é inválido.';
    }
}
