<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\NFSenDTO;
use Facades\Sysborg\FocusNFe\app\Services\NFSeNacional;
use Sysborg\FocusNFe\app\Http\Requests\NFSeNacionalRequest;


class NFSeNacionalController extends Controller
{
  
    public function store(NFSeNacionalRequest $request)
    {
        $dto = NFSeNDTO::fromArray($request->validated());
        return response()->json(NFSeNacional::envia($dto), 201);
    }
    

    public function show(string $referencia)
    {
        return response()->json(NFSeNacional::consulta($referencia));
    }

    
    public function destroy(string $referencia)
    {
        return response()->json(NFSeNacional::cancela($referencia));
    }
}
