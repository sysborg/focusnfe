<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\CTERecebidasDTO;
use Facades\Sysborg\FocusNfe\app\Services\CTERecebidas;
use Sysborg\FocusNfe\app\Http\Requests\CTERecebidasRequest;
use Sysborg\FocusNfe\app\Rules\CnpjRule;

/**
 * Controlador responsável por gerenciar CT-e Recebidos
 */
class CTERecebidasController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cnpj = (string) $request->query('cnpj', '');
        validator(['cnpj' => $cnpj], [
            'cnpj' => ['required', 'string', new CnpjRule()]
        ])->validate();

        return response()->json(CTERecebidas::consulta($cnpj));
    }

    /**
     * @param string $chave
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $chave)
    {
        return response()->json(CTERecebidas::consultaCTE($chave));
    }

    /**
     * @param string $cnpj
     * @return \Illuminate\Http\JsonResponse
     */
    public function consulta(string $cnpj)
    {
        return response()->json(CTERecebidas::consulta($cnpj));
    }

    /**
     * @param string $chave
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultaCTE(string $chave)
    {
        return response()->json(CTERecebidas::consultaCTE($chave));
    }

    /**
     * @param string $chave
     * @param CTERecebidasRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function informarDesacordo(string $chave, CTERecebidasRequest $request)
    {
        $dto = CTERecebidasDTO::fromArray($request->validated());
        return response()->json(CTERecebidas::informarDesacordo($chave, $dto));
    }

    /**
     * @param string $chave
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultaDesacordo(string $chave)
    {
        return response()->json(CTERecebidas::consultaDesacordo($chave));
    }
}
