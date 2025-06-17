<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class MessageSent implements ShouldBroadcast, ShouldQueue
{

    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chats.' . $this->chat->sender_id),
            new PrivateChannel('chats.' . $this->chat->receiver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

}
