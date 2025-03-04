<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Services\NFSeRecebidas;
use Sysborg\FocusNFe\app\Http\Requests\NFSeRecebidasRequest;

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
