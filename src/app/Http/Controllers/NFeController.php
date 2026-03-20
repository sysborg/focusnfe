<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFe;
use Sysborg\FocusNfe\app\Http\Requests\NFeRequest;

/**
 * Controlador responsável por gerenciar NF-e
 */
class NFeController extends Controller
{
    /**
     * @param NFeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NFeRequest $request)
    {
        $dto = NFeDTO::fromArray($request->validated());
        return response()->json(NFe::envia($dto, $request->input('referencia')), 201);
    }


    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia)
    {
        return response()->json(NFe::get($referencia));
    }

    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $referencia)
    {
        return response()->json(NFe::cancela($referencia));
    }

    /**
     * @param string $referencia
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartaCorrecao(string $referencia, Request $request)
    {
        return response()->json(NFe::cartaCorrecao($referencia, $request->all()));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function inutilizacoes()
    {
        return response()->json(NFe::inutilizacoes());
    }
}
