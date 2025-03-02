<?php

namespace Sysborg\FocusNFe\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\DTO\NFSeDTO;
use Sysborg\FocusNFe\App\Services\NFSe;
use Sysborg\FocusNFe\App\Http\Requests\NFSeRequest;

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

        $nfseService = new NFSe(config('focusnfe.token'));

        return response()->json($nfseService->envia($dto));

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $nfseService = new NFSe(config('focusnfe.token'));

        return response()->json($nfseService->get($id));
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

        $nfseService = new NFSe(config('focusnfe.token'));

        return response()->json($nfseService->cancela($id));

    }
}
