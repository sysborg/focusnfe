<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\CTERecebidasDTO;
use Sysborg\FocusNFe\app\Services\CTERecebidas;
use Sysborg\FocusNFe\app\Http\Requests\CTERecebidasRequest;

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
