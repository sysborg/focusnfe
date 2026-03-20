<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

/**
 * Requisição para validação de dados de NF-e Recebidas
 */
class NFeRecebidasRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'tipo' => 'required|string',
            'justificativa' => 'nullable|string',
        ];
    }
}
