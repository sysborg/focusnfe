<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\NFSeRecebidas;
use Sysborg\FocusNfe\app\Http\Requests\NFSeRecebidasRequest;

class NFSeRecebidasController extends Controller
{
 
    public function index(NFSeRecebidasRequest $request)
    {
        return response()->json(NFSeRecebidas::listByCnpj($request->cnpj));

        
    }

   
    public function show(string $chave)
    {
        return response()->json(NFSeRecebidas::getByChave($chave));
    }
}
