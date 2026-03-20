<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\NFeRecebidasDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFeRecebidas;
use Sysborg\FocusNfe\app\Http\Requests\NFeRecebidasRequest;

/**
 * Controlador responsável por gerenciar NF-e Recebidas
 */
class NFeRecebidasController extends Controller
{
    /**
     * @param string $cnpj
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByCnpj(string $cnpj)
    {
        return response()->json(NFeRecebidas::listByCnpj($cnpj));
    }


    /**
     * @param string $chave
     * @param NFeRecebidasRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function manifestar(string $chave, NFeRecebidasRequest $request)
    {
        $dto = NFeRecebidasDTO::fromArray($request->validated());
        return response()->json(NFeRecebidas::manifestar($chave, $dto));
    }


    /**
     * @param string $chave
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarManifesto(string $chave)
    {
        return response()->json(NFeRecebidas::consultarManifesto($chave));
    }
}
