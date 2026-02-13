<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Sysborg\FocusNfe\app\DTO\EmpresaDTO;

class EmpresaRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return EmpresaDTO::rules();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return EmpresaDTO::messages();
    }
}
