<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = \App\Models\Chat::with('customer')->where('customer_id', $request->customer->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $chats,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $chat = \App\Models\Chat::create([
            'customer_id' => $request->customer->id,
            'message' => $request->message,
            'is_reply' => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $chat,
        ]);
    }
}
