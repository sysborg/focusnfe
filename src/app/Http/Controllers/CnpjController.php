<?php

namespace Sysborg\FocusNFe\App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\Rules\CnpjRule;
use Sysborg\FocusNFe\App\Services\Cnpjs;

class CnpjController extends Controller
{
    
    public function getCnpj(Request $request, string $cnpj)
    {
        $request->validate([
            'cnpj' => ['required', 'string', new CnpjRule($cnpj)]
        ]);

        $cnpjService = new Cnpjs($cnpj);

        return response()->json($cnpjService->get($cnpj));
    }
}

