<?php

namespace Sysborg\FocusNfe\app\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento disparado quando uma NFSe é cancelada
 */
class NFSeCancelada
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $data;

    /**
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
