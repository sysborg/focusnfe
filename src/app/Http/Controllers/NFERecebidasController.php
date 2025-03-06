<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\NFeRecebidasDTO;
use Sysborg\FocusNFe\app\Services\NFeRecebidas;
use Sysborg\FocusNFe\app\Http\Requests\NFeRecebidasRequest;

class NFeRecebidasController extends Controller
{
   
    public function listByCnpj(string $cnpj)
    {
        return response()->json(NFeRecebidas::listByCnpj($cnpj));
    }


    public function manifestar(string $chave, NFeRecebidasRequest $request)
    {
        $dto = NFeRecebidasDTO::fromArray($request->validated());
        return response()->json(NFeRecebidas::manifestar($chave, $dto));
    }

  
    public function consultarManifesto(string $chave)
    {
        return response()->json(NFeRecebidas::consultarManifesto($chave));
    }
}
