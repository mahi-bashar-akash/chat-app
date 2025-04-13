<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSend implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /***
        Create a new event instance.
    ******
        @param \App\Models\Message $message
    ***/
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /***
         Get the channels the event should broadcast on.
    ******
        @return \Illuminate\Broadcasting\Channel|array
     ***/
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }

    /***
        Customize the broadcast name.
    ******
        @return string
    ***/
    public function broadcastAs()
    {
        return 'MessageSend';
    }

    /***
        Get the data to broadcast.
    ******
        @return array
    ***/
    public function broadcastWith()
    {
        return ['message' => $this->message];
    }

}
