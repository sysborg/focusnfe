<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Http\Requests\NFSeRequest;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Facades\Sysborg\FocusNfe\app\Services\NFSe;

class NFSeController extends Controller
{
    public function store(NFSeRequest $request)
    {
        $dto = NFSeDTO::fromArray($request->validated());
        $ref = (string) ($request->query('ref') ?? $request->input('referencia'));
        validator(['ref' => $ref], ['ref' => ['required', 'string']])->validate();
        return response()->json(NFSe::envia($dto, $ref), 201);
    }

    public function show(string $id)
    {
        return response()->json(NFSe::get($id));
    }

    public function destroy(string $id)
    {
        return response()->json(NFSe::cancela($id));
    }
}
