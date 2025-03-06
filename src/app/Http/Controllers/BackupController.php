<?php 

namespace Sysborg\FocusNFe\App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNFe\app\Services\Backups;
use Log;

class HooksController extends Controller
{
    public function request(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|min:14|max:14',
        ]);

        return response()->json(Backups::get($request->cnpj));
    }
}
