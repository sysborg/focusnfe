<?php

namespace Sysborg\FocusNFe\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\Rules\CepRule;
use Sysborg\FocusNFe\App\Services\Cep;

class CepController extends Controller
{
    
    public function getCnpj(Request $request, string $cep)
    {
        $request->validate([
            'cnpj' => ['required', 'string', new CepRule($cep)]
        ]);

        $cnpjService = new Cep($cep);

        return response()->json($cnpjService->get($cep));
    }
}

