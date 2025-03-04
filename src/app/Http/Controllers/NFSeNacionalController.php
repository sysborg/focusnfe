<?php

namespace Sysborg\FocusNFe\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\NFSenDTO;
use Sysborg\FocusNFe\App\Services\NFSeNacional;


class NFSeNacionalController extends Controller
{
  
    public function store(Request $request)
    {
        $dto = NFSenDTO::fromArray($request->validated());
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
