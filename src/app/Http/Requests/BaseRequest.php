<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe base para requisições HTTP com autorização padrão
 */
abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
