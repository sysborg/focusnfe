<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

/**
 * Requisição para validação de envio de arquivo de NFS-e
 */
class NFSeArquivoRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'arquivo' => ['required', 'file', 'mimes:json,txt,xml'],
        ];
    }
}
