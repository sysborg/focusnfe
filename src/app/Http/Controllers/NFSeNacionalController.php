<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFSeNacional;
use Sysborg\FocusNfe\app\Http\Requests\NFSeNacionalRequest;

/**
 * Controlador responsável por gerenciar NFS-e Nacional
 */
class NFSeNacionalController extends Controller
{
    /**
     * @param NFSeNacionalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NFSeNacionalRequest $request)
    {
        $dto = NFSeNDTO::fromArray($request->validated());
        return response()->json(NFSeNacional::envia($dto), 201);
    }


    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia)
    {
        return response()->json(NFSeNacional::consulta($referencia));
    }

    /**
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $referencia)
    {
        return response()->json(NFSeNacional::cancela($referencia));
    }
}
