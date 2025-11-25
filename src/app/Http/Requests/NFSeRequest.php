<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;

class NFSeRequest extends FormRequest
{
    public function rules(): array
    {
        return NFSeDTO::rules();
    }

    public function messages(): array
    {
        return NFSeDTO::messages();
    }
}
