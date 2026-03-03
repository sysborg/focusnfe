<?php

namespace Sysborg\FocusNfe\app\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HooksReceived
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $data;
    public string $url_referrer;

    public function __construct(array $data, string $url_referrer)
    {
        $this->data = $data;
        $this->url_referrer = $url_referrer;
    }
}
