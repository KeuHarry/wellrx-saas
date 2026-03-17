<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Diffusion immédiate (ShouldBroadcastNow) pour que le message parte vers Reverb
 * sans passer par une queue. PrivateChannel = canal privé, l’auth se fait via broadcasting/auth.
 */
class NewNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.' . $this->data['userId']);
    }

    /**
     * Nom de l'événement côté client (Echo.listen('NewNotificationEvent', ...)).
     */
    public function broadcastAs(): string
    {
        return 'NewNotificationEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'type' => $this->data['type'],
        ];
    }
}
