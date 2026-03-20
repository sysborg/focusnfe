<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFCe;
use Sysborg\FocusNfe\app\Http\Requests\NFCeRequest;

/**
 * Controlador responsável por gerenciar NFC-e
 */
class NFCeController extends Controller
{
    /**
     * @param NFCeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NFCeRequest $request)
    {
        $dto = NFCeDTO::fromArray($request->validated());
        return response()->json(NFCe::envia($dto), 201);
    }


    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia)
    {
        return response()->json(NFCe::get($referencia));
    }

    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $referencia)
    {
        return response()->json(NFCe::cancela($referencia));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function inutilizacoes()
    {
        return response()->json(NFCe::inutilizacoes());
    }


    /**
     * @param string $referencia
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registraEconf(string $referencia, Request $request)
    {
        return response()->json(NFCe::registraEconf($referencia, $request->all()));
    }


    /**
     * @param string $referencia
     * @param string $protocolo
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultaEconf(string $referencia, string $protocolo)
    {
        return response()->json(NFCe::consultaEconf($referencia, $protocolo));
    }


    /**
     * @param string $referencia
     * @param string $protocolo
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelaEconf(string $referencia, string $protocolo)
    {
        return response()->json(NFCe::cancelaEconf($referencia, $protocolo));
    }
}
