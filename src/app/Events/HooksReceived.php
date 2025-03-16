<?php
namespace Sysborg\FocusNFe\app\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HooksReceived {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;
    public string $url_referrer;

    public function __construct(array $data, string $url_referrer)
    {
        $this->data = $data;
        $this->url_referrer = $url_referrer;
    }
}
