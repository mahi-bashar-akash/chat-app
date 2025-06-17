<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log; // Add this at the top

class MessageSent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
        Log::info('MessageSent event constructed for chat ID: ' . $chat->id);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('private-chats.' . $this->chat->receiver_id);
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastWith()
    {
        Log::info('Broadcasting chat message:', ['chat' => $this->chat]);
        return ['chat' => $this->chat];
    }
}
