<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Facades\Sysborg\FocusNFe\app\Services\MDFe;

class MDFeController extends Controller
{
   
    public function store(Request $request)
    {
        return response()->json(MDFe::envia($request->all()), 201);
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

  
    public function incluiCondutor(Request $request, string $referencia)
    {
        return response()->json(MDFe::incluiCondutor($referencia, $request->all()));
    }

   
    public function incluiDFe(Request $request, string $referencia)
    {
        return response()->json(MDFe::incluiDFe($referencia, $request->all()));
    }

   
    public function encerra(string $referencia)
    {
        return response()->json(MDFe::encerra($referencia));
    }
}
