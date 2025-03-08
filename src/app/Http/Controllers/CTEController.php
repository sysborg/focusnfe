<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Http\Requests\CTeRequest;
use Sysborg\FocusNFe\app\DTO\CTeDTO;
use Facades\Sysborg\FocusNFe\app\Services\CTe;
use Illuminate\Http\JsonResponse;

class CTeController extends Controller
{
    /**
     * Envia um novo CTe para processamento.
     * 
     * @param CTeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CTeRequest $request): JsonResponse
    {
        $dto = CTeDTO::fromArray($request->validated());

        return response()->json(CTe::envia($dto->toArray(), $dto->referencia), 201);
    }

    /**
     * Consulta um CTe pelo ID.
     * 
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia): JsonResponse
    {
        return response()->json(CTe::consulta($referencia));
    }

    /**
     * Cancela um CTe.
     * 
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $referencia): JsonResponse
    {
        return response()->json(CTe::cancela($referencia));
    }

      /**
     * Cria uma Carta de Correção para um CTe.
     * 
     * @param string $referencia
     * @param CTeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cartaCorrecao(string $referencia, CTeRequest $request): JsonResponse
    {
        $dto = CTeDTO::fromArray($request->validated());
        return response()->json(CTe::cartaCorrecao($referencia, $dto->toArray()));
    }
}
