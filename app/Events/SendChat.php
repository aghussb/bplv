<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SendChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($user,$chat)
    {
        $this->chat = $chat;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): String
    {
        return new Channel('chat-channel');
        // return [
        //     new PrivateChannel('channel-name'),
        // ];
    }

    public function broadcastAs(): String
    {
        return 'ChatEvent';
    }

    public function broadcastWith() : array
    {
        return [
            'user'=>$this->user,
            'chat'=>$this->chat,
        ];
    }

}
