<?php

namespace Sysborg\FocusNFe\app\Services;

abstract class EventHelper
{
    /**
     * Dispara um evento
     * 
     * @param string $event
     * @param array $data
     * @return void
     */
    public function dispatch(string $event, Illuminate\Http\Client\Response $response): void
    {
        event(new $event([
            'status' => $request->status(),
            'data' => $request->json(),
            'success' => $request->ok(),
        ]));
    }
}
