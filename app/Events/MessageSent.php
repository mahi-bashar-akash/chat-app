<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Message $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('chats.' . $this->chat->receiver_id),
            new PrivateChannel('chats.' . $this->chat->sender_id)
        ];
    }

    public function broadcastWith()
    {
        return [
            'chat' => $this->chat
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
