<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\NFSeRecebidas;
use Sysborg\FocusNfe\app\Http\Requests\NFSeRecebidasRequest;

/**
 * Controlador responsável por gerenciar NFS-e Recebidas
 */
class NFSeRecebidasController extends Controller
{
    /**
     * @param NFSeRecebidasRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(NFSeRecebidasRequest $request)
    {
        return response()->json(NFSeRecebidas::listByCnpj($request->cnpj));


    }


    /**
     * @param string $chave
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $chave)
    {
        return response()->json(NFSeRecebidas::getByChave($chave));
    }
}
