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
    public string $urlReferrer;

    public function __construct(array $data, string $urlReferrer)
    {
        $this->data = $data;
        $this->urlReferrer = $urlReferrer;
    }
}
