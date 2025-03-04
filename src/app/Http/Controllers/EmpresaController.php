<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\EmpresaDTO;
use Sysborg\FocusNFe\app\Http\Requests\EmpresaRequest;
use Sysborg\FocusNfe\app\Services\Empresas;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(Empresas::list(
            $request->query('offset', 1),
            $request->query('cnpj', null),
            $request->query('cpf', null)
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmpresaRequest $request)
    {
        $dto = EmpresaDTO::fromArray($request->validated());
        return response()->json(Empresas::create($dto), 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Empresas::get($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmpresaRequest $request, string $id)
    {
        $dto = EmpresaDTO::fromArray($request->validated());
        return response()->json(Empresas::update($id, $dto));
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(Empresas::delete($id));
    }
}
