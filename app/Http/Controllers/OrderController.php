<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::with(['items.product', 'customer', 'paymentMethod'])->get();
        $counts = [
            'all' => $orders->count(),
            'paid' => $orders->where('status', 'paid')->count(),
            'unpaid' => $orders->where('status', 'unpaid')->count(),
            'shipped' => $orders->where('status', 'shipped')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
            'canceled' => $orders->where('status', 'canceled')->count(),
        ];
        if ($status != null) {
            $orders = $orders->where('status', $status);
        }
        return view('orders.index', compact('orders', 'status', 'counts'));
    }

    // confirm payment
    public function confirmPayment(Request $request, Order $order)
    {
        $order->status = 'paid';
        $order->save();
        return redirect()->route('orders.index');
    }

    // cancel order
    public function cancel(Request $request, Order $order)
    {
        $order->status = 'canceled';
        $order->save();
        return redirect()->route('orders.index');
    }

    // ship order
    public function ship(Request $request, Order $order)
    {
        $order->status = 'shipped';
        $order->save();
        return redirect()->route('orders.index');
    }
}
