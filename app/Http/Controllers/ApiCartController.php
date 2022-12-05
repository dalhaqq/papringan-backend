<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = \App\Models\CartItem::with('product')->where('customer_id', $request->customer->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $cartItems,
        ]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $cartItem = \App\Models\CartItem::where('customer_id', $request->customer->id)->where('product_id', $request->product_id)->first();
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            $cartItem = \App\Models\CartItem::create([
                'customer_id' => $request->customer->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cartItem,
        ]);
    }

    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $cartItem = \App\Models\CartItem::where('customer_id', $request->customer->id)->where('product_id', $request->product_id)->first();
        if ($cartItem) {
            $cartItem->delete();
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $cartItem = \App\Models\CartItem::where('customer_id', $request->customer->id)->where('product_id', $request->product_id)->first();
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cartItem,
        ]);
    }
}
