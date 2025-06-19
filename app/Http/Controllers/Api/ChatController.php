<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class ChatController extends BaseController
{

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $userId = $request->user()->id;
        $chatWithId = $request->query('chat');
        if (!$chatWithId) {
            return response()->json(['error' => 'chat with query parameter is required'], 422);
        }
        $chats = Message::where(function ($query) use ($userId, $chatWithId) {
            $query->where('sender_id', $userId)->where('receiver_id', $chatWithId);
        })->orWhere(function ($query) use ($userId, $chatWithId) {
            $query->where('sender_id', $chatWithId)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();
        return response()->json(['chats' => $chats], 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->message === '__typing__') {
            event(new MessageSent((object)[
                'sender_id' => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'message' => '__typing__',
            ]));
            return response()->json(['chat' => 'typing event sent'], 200);
        }
        $chat = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        $message = Message::with(['sender','receiver'])->find($chat->id);
        event(new MessageSent($message));
        return response()->json(['chat' => $message], 201);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        try {
            $chat = Message::findOrFail($id);
            if ($chat->sender_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $chat->delete();
            return response()->json(['chat' => 'Chat deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Chat not found'], 404);
        }
    }

    public function clear(): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();
        Message::where('sender_id', $userId)->orWhere('receiver_id', $userId)->delete();
        return response()->json(['chat' => 'Your chat history cleared successfully'], 200);
    }

}
