<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\Backups;

/**
 * Controlador responsável por consultar backups de empresas
 */
class BackupController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function request(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|min:14|max:14',
        ]);

        return response()->json(Backups::get($request->cnpj));
    }
}
