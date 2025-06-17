<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class MessageSent implements ShouldBroadcast
{
    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chats.' . $this->chat->receiver_id);
    }

    public function broadcastAs()
    {
        return 'MessageSend';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->chat->id,
            'sender_id' => $this->chat->sender_id,
            'receiver_id' => $this->chat->receiver_id,
            'content' => $this->chat->content,
            'created_at' => $this->chat->created_at->toDateTimeString(),
        ];
    }
}
