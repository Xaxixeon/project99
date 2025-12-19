<?php

namespace App\Events;

use App\Models\PreorderNote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PreorderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PreorderNote $note;

    public function __construct(PreorderNote $note)
    {
        $this->note = $note;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('preorder-channel');
    }

    public function broadcastAs(): string
    {
        return 'preorder.updated';
    }
}
