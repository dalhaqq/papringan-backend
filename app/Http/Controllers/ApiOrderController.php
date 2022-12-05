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

    public function pesanan(Request $request)
    {
        $pesanan = Pesanan::where('customer_id', $request->customer->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $pesanan,
        ]);
    }

    public function pesan(Request $request)
    {
        $pesanan = \App\Models\Pesanan::create([
            'customer_id' => $request->customer->id,
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'total' => $request->total,
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $pesanan,
        ]);
    }

    public function hapus(Request $request)
    {
        $pesanan = \App\Models\Pesanan::where('customer_id', $request->customer->id)->where('id', $request->id)->first();
        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan not found',
            ], 404);
        }
        $pesanan->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan deleted',
        ]);
    }
}
