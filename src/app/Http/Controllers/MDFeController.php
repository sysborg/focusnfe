<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\MDFeDTO;
use Facades\Sysborg\FocusNfe\app\Services\MDFe;
use Sysborg\FocusNfe\app\Http\Requests\MDFeRequest;

class MDFeController extends Controller
{
   
    public function store(MDFeRequest $request)
{
    $dto = MDFeDTO::fromArray($request->validated());
    return response()->json(MDFe::create($dto), 201);
}


    /**
     * Consulta um MDF-e pelo ID.
     */
    public function show(string $referencia)
    {
        return response()->json(MDFe::consulta($referencia));
    }

    public function destroy(string $referencia)
    {
        return response()->json(MDFe::cancela($referencia));
    }

  
    public function incluiCondutor(MDFeRequest $request, string $referencia)
    {
        return response()->json(MDFe::incluiCondutor($referencia, $request->all()));
    }

   
    public function incluiDFe(MDFeRequest $request, string $referencia)
    {
        return response()->json(MDFe::incluiDFe($referencia, $request->all()));
    }

   
    public function encerra(string $referencia)
    {
        return response()->json(MDFe::encerra($referencia));
    }
}
