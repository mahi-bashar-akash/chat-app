<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ChatController extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;

    public function list()
    {
        $messages = Message::all();
        return response()->json(['messages' => $messages], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|string',
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->content = $request->content;
        $message->save();
        return response()->json(['message' => $message], 201);
    }

    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        return response()->json(['message' => $message], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $message->content = $request->content;
        $message->save();
        return response()->json(['message' => $message], 200);
    }

    public function delete($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $message->delete();
        return response()->json(['message' => 'Message deleted successfully'], 200);
    }

    public function clear()
    {
        Message::truncate();
        return response()->json(['message' => 'All messages cleared successfully'], 200);
    }

}
