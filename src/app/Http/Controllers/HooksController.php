<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Http\Requests\HooksRequest;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Sysborg\FocusNfe\app\Services\FocusNfeLogger;

/**
 * Controlador responsável por receber e processar webhooks
 */
class HooksController extends Controller
{
    /**
     * @param HooksRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(HooksRequest $request)
    {
        try {
            event(new HooksReceived($request->all(), $request->header('referer') ?? ''));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            FocusNfeLogger::error('FocusNfe.Webhooks: Erro ao processar o webhook: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
