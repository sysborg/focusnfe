<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\CTERecebidasDTO;
use Facades\Sysborg\FocusNfe\app\Services\CTERecebidas;
use Sysborg\FocusNfe\app\Http\Requests\CTERecebidasRequest;

class CTERecebidasController extends Controller
{
 
    public function consulta(string $cnpj)
    {
        return response()->json(CTERecebidas::consulta($cnpj));
    }
 
    public function consultaCTE(string $chave)
    {
        return response()->json(CTERecebidas::consultaCTE($chave));
    }

    public function informarDesacordo(string $chave, CTERecebidasRequest $request)
    {
        $dto = CTERecebidasDTO::fromArray($request->validated());
        return response()->json(CTERecebidas::informarDesacordo($chave, $dto));
    }
 
    public function consultaDesacordo(string $chave)
    {
        return response()->json(CTERecebidas::consultaDesacordo($chave));
    }
}
