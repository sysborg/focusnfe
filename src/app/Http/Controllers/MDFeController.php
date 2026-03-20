<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\MDFeDTO;
use Facades\Sysborg\FocusNfe\app\Services\MDFe;
use Sysborg\FocusNfe\app\Http\Requests\MDFeRequest;

/**
 * Controlador responsável por gerenciar MDF-e
 */
class MDFeController extends Controller
{
    /**
     * @param MDFeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MDFeRequest $request)
    {
        $dto = MDFeDTO::fromArray($request->validated());
        return response()->json(MDFe::create($dto), 201);
    }


    /**
     * Consulta um MDF-e pelo ID.
     *
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia)
    {
        return response()->json(MDFe::consulta($referencia));
    }

    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $referencia)
    {
        return response()->json(MDFe::cancela($referencia));
    }

    /**
     * @param MDFeRequest $request
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function incluiCondutor(MDFeRequest $request, string $referencia)
    {
        return response()->json(MDFe::incluiCondutor($referencia, $request->all()));
    }


    /**
     * @param MDFeRequest $request
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function incluiDFe(MDFeRequest $request, string $referencia)
    {
        return response()->json(MDFe::incluiDFe($referencia, $request->all()));
    }


    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function encerra(string $referencia)
    {
        return response()->json(MDFe::encerra($referencia));
    }
}
