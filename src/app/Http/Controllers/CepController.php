<?php 

namespace Sysborg\FocusNFe\App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\Rules\CepRule;
use Sysborg\FocusNFe\App\Services\CEP;

class CepController extends Controller
{
    public function getCep(Request $request, string $cep)
    {
        $request->validate([
            'cep' => ['required', 'string', new CepRule($cep)]
        ]);

        return response()->json(Cep::get($cep));
    }
}
