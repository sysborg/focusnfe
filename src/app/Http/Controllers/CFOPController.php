<?php 

namespace Sysborg\FocusNFe\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Services\CFOP; 

class CfopController extends Controller
{
   
    
    public function index(Request $request)
    {
        return response()->json(Cfop::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        ));
    }

 
    public function show(string $codigo)
    {
        return response()->json(Cfop::get($codigo));
    }
}
