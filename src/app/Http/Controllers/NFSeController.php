<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Sysborg\FocusNfe\app\Http\Requests\NFSeRequest;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\Services\NFSe;

class NFSeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(NFSe::list(
            $request->query('offset', 1),
            $request->query('cnpj', null),
            $request->query('cpf', null)
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NFSeRequest $request)
    {
        $dto = NFSeDTO::fromArray($request->validated());
        return response()->json(NFSe::create($dto), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(NFSe::get($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NFSeRequest $request, string $id)
    {
        $dto = NFSeDTO::fromArray($request->validated());
        return response()->json(NFSe::update($id, $dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(NFSe::cancela($id));
    }
}