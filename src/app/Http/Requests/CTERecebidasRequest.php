<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

/**
 * Requisição para validação de dados de CT-e Recebidas
 */
class CTERecebidasRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'observacoes' => 'required|string|min:15|max:255',
        ];
    }
}
