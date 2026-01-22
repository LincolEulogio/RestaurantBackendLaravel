<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load('table', 'waiter');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders.' . $this->order->id),
            new Channel('waiter.' . ($this->order->waiter_id ?? 'public')),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }
}
