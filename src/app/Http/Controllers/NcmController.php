<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Services\NCM;

class NcmController extends Controller
{

    public function index(Request $request)
    {
        return response()->json(Ncm::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        ));
    }


    public function show(string $codigo)
    {
        return response()->json(Ncm::get($codigo));
    }
}
