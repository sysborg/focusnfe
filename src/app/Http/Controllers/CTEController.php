<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Http\Requests\CTeRequest;
use Sysborg\FocusNFe\app\DTO\CTeDTO;
use Sysborg\FocusNFe\app\Services\CTe;

class CTeController extends Controller
{
    /**
     * Envia um novo CTe para processamento.
     */
    public function store(CTeRequest $request)
    {
        $dto = CTeDTO::fromArray($request->validated());
        return response()->json(CTe::envia($dto->toArray(), $dto->referencia), 201);
    }

    /**
     * Consulta um CTe pelo ID.
     */
    public function show(string $referencia)
    {
        return response()->json(CTe::consulta($referencia));
    }

    /**
     * Cancela um CTe.
     */
    public function destroy(string $referencia)
    {
        return response()->json(CTe::cancela($referencia));
    }
}
