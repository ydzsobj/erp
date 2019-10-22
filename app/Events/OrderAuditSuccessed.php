<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderAuditSuccessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_ids;

    public $remark;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_ids, $remark)
    {
        //
        $this->order_ids = $order_ids;
        $this->remark = $remark;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
