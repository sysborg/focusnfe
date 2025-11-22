<?php

namespace Sysborg\FocusNFe\app\Services;
use Illuminate\Http\Client\Response;

abstract class EventHelper
{
    /**
     * Dispara um evento
     * 
     * @param string $event
     * @param array $data
     * @return void
     */
    public function dispatch(string $event, Response $response): void
    {
        event(new $event([
            'status' => $response->status(),
            'data' => $response->json(),
            'success' => $response->ok(),
        ]));
    }
}
