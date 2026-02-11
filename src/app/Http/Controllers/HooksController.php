<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Http\Requests\HooksRequest;
use Sysborg\FocusNfe\app\Events\HooksReceived;
use Log;

class HooksController extends Controller
{
    public function webhook(HooksRequest $request)
    {
        try {
            event(new HooksReceived($request->all(), $request->header('referer') ?? ''));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('FocusNfe.Webhooks: Erro ao processar o webhook: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
