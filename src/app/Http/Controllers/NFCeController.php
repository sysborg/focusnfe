<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\NFCeDTO;
use Sysborg\FocusNFe\app\Services\NFCe;
use Sysborg\FocusNFe\app\Http\Requests\NFCeRequest;

class NFCeController extends Controller
{
  
    public function store(NFCeRequest $request)
    {
        $dto = NFCeDTO::fromArray($request->validated());
        return response()->json(NFCe::envia($dto), 201);
    }

  
    public function show(string $referencia)
    {
        return response()->json(NFCe::get($referencia));
    }

 
    public function destroy(string $referencia)
    {
        return response()->json(NFCe::cancela($referencia));
    }

  
    public function inutilizacoes()
    {
        return response()->json(NFCe::inutilizacoes());
    }

 
    public function registraEconf(string $referencia, Request $request)
    {
        return response()->json(NFCe::registraEconf($referencia, $request->all()));
    }


    public function consultaEconf(string $referencia, string $protocolo)
    {
        return response()->json(NFCe::consultaEconf($referencia, $protocolo));
    }

   
    public function cancelaEconf(string $referencia, string $protocolo)
    {
        return response()->json(NFCe::cancelaEconf($referencia, $protocolo));
    }
}
