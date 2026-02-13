<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\Backups;
use Log;

class BackupController extends Controller
{
    public function request(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|min:14|max:14',
        ]);

        return response()->json(Backups::get($request->cnpj));
    }
}
