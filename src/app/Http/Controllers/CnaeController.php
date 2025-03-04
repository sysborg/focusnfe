<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Rules\CnaeRule;
use Sysborg\FocusNFe\app\Services\CNAE;

class CnaeController extends Controller
{
   
    public function index(Request $request)
    {
   
        return response()->json(Cnae::list(
            $request->query('offset', 1), 
            $request->query('codigo', null), 
            $request->query('descricao', null) 
        ));
    }


    public function show(Request $request, string $codigo)
    {

        $request->validate([
            'cnae' => ['required', 'string', new CnaeRule($codigo)]
        ]);

        return response()->json(CNAE::get($codigo));

    }
}
