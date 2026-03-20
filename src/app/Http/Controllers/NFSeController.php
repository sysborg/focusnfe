<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Sysborg\FocusNfe\app\Http\Requests\NFSeRequest;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFSe;

/**
 * Controlador responsável por gerenciar NFS-e
 */
class NFSeController extends Controller
{
    /**
     * @param NFSeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NFSeRequest $request)
    {
        $dto = NFSeDTO::fromArray($request->validated());
        $ref = (string) ($request->query('ref') ?? $request->input('referencia'));
        validator(['ref' => $ref], ['ref' => ['required', 'string']])->validate();
        return response()->json(NFSe::envia($dto, $ref), 201);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        return response()->json(NFSe::get($id));
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $id)
    {
        $payload = $request->validate([
            'justificativa' => ['required', 'string'],
        ]);

        return response()->json(NFSe::cancela($id, $payload['justificativa']));
    }
}
