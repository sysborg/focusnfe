<?php 

namespace Sysborg\FocusNFe\app\Rules;

use Illuminate\Contracts\Validation\Rule;

class CnaeRule implements Rule
{
  
    public function passes($attribute, $value)
    {
        return preg_match('/^\d{6}$/', $value);
    }


    public function message()
    {
        return 'O código CNAE deve conter exatamente 6 dígitos numéricos.';
    }
}
