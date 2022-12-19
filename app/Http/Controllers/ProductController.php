<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show(Request $request, Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create(Request $request)
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|integer',
            'description' => 'required',
            'image' => 'required|image',
            'stock' => 'required|integer',
            'weight' => 'required|integer',
            'dimension_x' => 'required|integer',
            'dimension_y' => 'required|integer',
            'dimension_z' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image->store('public/products'),
            'description' => $request->description,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'dimension_x' => $request->dimension_x,
            'dimension_y' => $request->dimension_y,
            'dimension_z' => $request->dimension_z,
        ]);

        return redirect()->route('products.index');
    }

    public function edit(Request $request, Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->image) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required|integer',
                'description' => 'required',
                'image' => 'required|image',
                'stock' => 'required|integer',
                'weight' => 'required|integer',
                'dimension_x' => 'required|integer',
                'dimension_y' => 'required|integer',
                'dimension_z' => 'required|integer'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required|integer',
                'description' => 'required',
                'stock' => 'required|integer',
                'weight' => 'required|integer',
                'dimension_x' => 'required|integer',
                'dimension_y' => 'required|integer',
                'dimension_z' => 'required|integer'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image ? $request->image->store('public/products') : $product->image,
            'description' => $request->description,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'dimension_x' => $request->dimension_x,
            'dimension_y' => $request->dimension_y,
            'dimension_z' => $request->dimension_z,
        ]);

        return redirect()->route('products.index');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
