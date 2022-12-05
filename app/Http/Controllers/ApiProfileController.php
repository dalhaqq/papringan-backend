<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiProfileController extends Controller
{
    public function show(Request $request)
    {
        $customer = $request->customer;
        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function update(Request $request)
    {
        $customer = $request->customer;
        $customer->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $customer
        ]);
    }

    public function updateName(Request $request)
    {
        $customer = $request->customer;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function updateEmail(Request $request)
    {
        $customer = $request->customer;
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:customers,email,' . $customer->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer->update([
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function updatePhone(Request $request)
    {
        $customer = $request->customer;
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer->update([
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function updateAddress(Request $request)
    {
        $customer = $request->customer;
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer->update([
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function updateCity(Request $request)
    {
        $customer = $request->customer;
        $validator = Validator::make($request->all(), [
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer->update([
            'city_id' => $request->city_id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $customer,
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer = $request->customer;
        if (Hash::check($request->old_password, $customer->password)) {
            $customer->password = Hash::make($request->password);
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Password changed',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Old password is wrong',
        ], 400);
    }

    public function changeImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer = $request->customer;
        $image = $request->file('image');
        $image->move(public_path('images/customers'), $customer->id . '.' . $image->extension());
        $customer->image = $customer->id . '.' . $image->extension();
        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Image changed',
        ]);
    }
}
