<?php 

namespace Sysborg\FocusNFe\App\Http\Controllers;

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

        return response()->json(Cnpjs::get($cnpj));
    }
}
