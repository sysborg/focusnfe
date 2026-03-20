<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Sysborg\FocusNfe\app\Rules\CnpjRule;

/**
 * Requisição para validação de dados de NFS-e Recebidas
 */
class NFSeRecebidasRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'cnpj' => ['required', 'string', new CnpjRule()],
        ];
    }


}
