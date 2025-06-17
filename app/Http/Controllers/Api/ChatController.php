<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Events\MessageSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ChatController extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;

    public function list(Request $request)
    {
        $userId = $request->user()->id;
        $chatWithId = $request->query('chat_with');

        if (!$chatWithId) {
            return response()->json(['error' => 'chat_with query parameter is required'], 422);
        }

        $messages = Message::where(function ($query) use ($userId, $chatWithId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $chatWithId);
        })->orWhere(function ($query) use ($userId, $chatWithId) {
            $query->where('sender_id', $chatWithId)
                ->where('receiver_id', $userId);
        })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        $updatedMessage = Message::with(['sender','receiver'])->find($message->id);

        event(new MessageSend($updatedMessage));

        return response()->json(['message' => $updatedMessage], 201);
    }

    public function delete($id)
    {
        try {
            $message = Message::findOrFail($id);

            if ($message->sender_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $message->delete();

            return response()->json(['message' => 'Message deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Message not found'], 404);
        }
    }

    public function clear()
    {
        $userId = Auth::id();

        Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->delete();

        return response()->json(['message' => 'Your chat history cleared successfully'], 200);
    }

}
