<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        $products = \App\Models\Product::all();
        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function search(Request $request, $keyword)
    {
        $products = \App\Models\Product::where('name', 'like', "%$keyword%")->get();
        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function show(Request $request, $id)
    {
        $product = \App\Models\Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 'success',
                'data' => $product,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Product not found',
        ], 404);
    }
}
