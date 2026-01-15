<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFe;
use Sysborg\FocusNfe\app\Http\Requests\NFeRequest;

class NFeController extends Controller
{
    
    public function store(NFeRequest $request)
    {
        $dto = NFeDTO::fromArray($request->validated());
        return response()->json(NFe::envia($dto, $request->input('referencia')), 201);
    }

 
    public function show(string $referencia)
    {
        return response()->json(NFe::get($referencia));
    }


    public function destroy(string $referencia)
    {
        return response()->json(NFe::cancela($referencia));
    }

   
    public function cartaCorrecao(string $referencia, Request $request)
    {
        return response()->json(NFe::cartaCorrecao($referencia, $request->all()));
    }

 
    public function inutilizacoes()
    {
        return response()->json(NFe::inutilizacoes());
    }
}
