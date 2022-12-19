<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class ApiOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = \App\Models\Order::with('items.product')->where('customer_id', $request->customer->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = \App\Models\Order::with('items.product')->where('customer_id', $request->customer->id)->where('id', $id)->first();
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }
}
