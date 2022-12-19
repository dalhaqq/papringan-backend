<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCheckoutController extends Controller
{
    public function checkoutProduct(Request $request)
    {
        $customer = $request->customer;
        if ($customer->city_id == null || $customer->address == null || $customer->phone == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please set your city, address, and phone number first',
            ], 400);
        }
        
        $cartItem = CartItem::create([
            'customer_id' => $customer->id,
            'product_id' => $request->product_id,
            'quantity' => 1,
        ]);

        $actualWeight = $cartItem->product->weight * $cartItem->quantity;
        $volumeWeight = $cartItem->product->dimension_x * $cartItem->product->dimension_y * $cartItem->product->dimension_z * $cartItem->quantity;
        $total = $cartItem->product->price * $cartItem->quantity;

        $volumeWeight = $volumeWeight / 5000;
        $weight = $actualWeight > $volumeWeight ? $actualWeight : $volumeWeight;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . env('RAJAONGKIR_ORIGIN') . "&destination=" . $customer->city_id . "&weight=" . $weight . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . env('RAJAONGKIR_API_KEY')
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return response()->json([
                'status' => 'error',
                'message' => $err,
            ], 400);
        }
        $response = json_decode($response);
        $shippingOptions = [];
        $id = 1;
        foreach ($response->rajaongkir->results as $result) {
            foreach ($result->costs as $cost) {
                if ($cost->cost[0]->etd != "") {
                    $shippingOptions[] = [
                        'id' => $id++,
                        'code' => $result->code,
                        'courier' => $result->name,
                        'service' => $cost->service,
                        'description' => $cost->description,
                        'cost' => $cost->cost[0]->value,
                        'etd' => $cost->cost[0]->etd,
                    ];
                }
            }
        }
        $paymentMethods = PaymentMethod::all();
        return response()->json([
            'status' => 'success',
            'data' => [
                'cartItems' => [$cartItem],
                'total' => $total,
                'shippingOptions' => $shippingOptions,
                'paymentMethods' => $paymentMethods,
            ],
        ]);
    }
    
    public function checkout(Request $request)
    {
        $customer = $request->customer;
        if ($customer->city_id == null || $customer->address == null || $customer->phone == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please set your city, address, and phone number first',
            ], 400);
        }
        $cartItemsId = $request->cart_items_id;
        $cartItems = [];
        $actualWeight = 0;
        $volumeWeight = 0;
        $total = 0;
        if (!$cartItemsId) {
            return response()->json([
                'status' => 'error',
                'message' => 'No cart item selected'
            ]);
        }
        foreach ($cartItemsId as $cartItemId) {
            $cartItem = CartItem::with('product')->find($cartItemId);
            if ($cartItem) {
                $cartItems[] = $cartItem;
                $actualWeight += $cartItem->product->weight * $cartItem->quantity;
                $volumeWeight += $cartItem->product->dimension_x * $cartItem->product->dimension_y * $cartItem->product->dimension_z * $cartItem->quantity;
                $total += $cartItem->product->price * $cartItem->quantity;
            }
        }
        $volumeWeight = $volumeWeight / 5000;
        $weight = $actualWeight > $volumeWeight ? $actualWeight : $volumeWeight;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . env('RAJAONGKIR_ORIGIN') . "&destination=" . $customer->city_id . "&weight=" . $weight . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . env('RAJAONGKIR_API_KEY')
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return response()->json([
                'status' => 'error',
                'message' => $err,
            ], 400);
        }
        $response = json_decode($response);
        $shippingOptions = [];
        $id = 1;
        foreach ($response->rajaongkir->results as $result) {
            foreach ($result->costs as $cost) {
                if ($cost->cost[0]->etd != "") {
                    $shippingOptions[] = [
                        'id' => $id++,
                        'code' => $result->code,
                        'courier' => $result->name,
                        'service' => $cost->service,
                        'description' => $cost->description,
                        'cost' => $cost->cost[0]->value,
                        'etd' => $cost->cost[0]->etd,
                    ];
                }
            }
        }
        $paymentMethods = PaymentMethod::all();
        return response()->json([
            'status' => 'success',
            'data' => [
                'cartItems' => $cartItems,
                'total' => $total,
                'shippingOptions' => $shippingOptions,
                'paymentMethods' => $paymentMethods,
            ],
        ]);
    }

    public function confirmCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_option_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $customer = $request->customer;
        $cartItemsId = $request->cart_items_id;
        $cartItems = [];
        if (!$cartItemsId) {
            return response()->json([
                'status' => 'error',
                'message' => 'No cart item selected'
            ]);
        }
        $actualWeight = 0;
        $volumeWeight = 0;
        $total = 0;
        foreach ($cartItemsId as  $cartItemId) {
            $cartItem = CartItem::with('product')->find($cartItemId);
            $cartItems[] = $cartItem;
            $actualWeight += $cartItem->product->weight * $cartItem->quantity;
            $volumeWeight += $cartItem->product->dimension_x * $cartItem->product->dimension_y * $cartItem->product->dimension_z * $cartItem->quantity;
            $total += $cartItem->product->price * $cartItem->quantity;
        }
        $volumeWeight = $volumeWeight / 5000;
        $weight = $actualWeight > $volumeWeight ? $actualWeight : $volumeWeight;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . env('RAJAONGKIR_ORIGIN') . "&destination=" . $customer->city_id . "&weight=" . $weight . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . env('RAJAONGKIR_API_KEY')
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return response()->json([
                'status' => 'error',
                'message' => $err,
            ], 400);
        }
        $response = json_decode($response);
        $id = 1;
        $shippingCost = 0;
        $shippingCourier = null;
        $shippingService = null;
        $shippingEtd = null;
        foreach ($response->rajaongkir->results as $result) {
            foreach ($result->costs as $cost) {
                if ($cost->cost[0]->etd != "") {
                    if ($id++ == $request->shipping_option_id) {
                        $shippingCost = $cost->cost[0]->value;
                        $shippingCourier = $result->name;
                        $shippingService = $cost->service;
                        $shippingEtd = $cost->cost[0]->etd;
                        break;
                    }
                }
            }
        }
        $order = \App\Models\Order::create([
            'customer_id' => $customer->id,
            'payment_method_id' => $request->payment_method_id,
            'address' => $customer->address,
            'phone' => $customer->phone,
            'shipping_cost' => $shippingCost,
            'shipping_courier' => $shippingCourier,
            'shipping_service_name' => $shippingService,
            'shipping_etd' => $shippingEtd,
            'total' => $total + $request->shipping_cost,
            'status' => 'unpaid',
        ]);
        foreach ($cartItems as $cartItem) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
            $cartItem->delete();
        }
        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function paymentMethods(Request $request)
    {
        $paymentMethods = PaymentMethod::all();
        return response()->json(
            [
                'status' => 'success',
                'data' => $paymentMethods
            ],
            200
        );
    }
}
