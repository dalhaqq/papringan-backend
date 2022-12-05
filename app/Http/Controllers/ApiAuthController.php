<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:customers',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:customers',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = bin2hex(random_bytes(32));
        Token::create([
            'customer_id' => $customer->id,
            'token' => $token,
        ]);

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $customer,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $customer = Customer::where('username', $request->username)->first();
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
            ], 401);
        }

        $token = bin2hex(random_bytes(32));
        Token::create([
            'customer_id' => $customer->id,
            'token' => $token,
        ]);

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $customer,
        ]);
    }
}
