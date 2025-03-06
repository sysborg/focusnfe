<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NFSeArquivoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'arquivo' => ['required', 'file', 'mimes:json,txt,xml'],
        ];
    }
}
