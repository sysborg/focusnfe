<?php

namespace Sysborg\FocusNfe\app\Http\Requests;


class NFSeArquivoRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'arquivo' => ['required', 'file', 'mimes:json,txt,xml'],
        ];
    }
}
