<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('customer')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // Register function
    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:50',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // Create customer after validation
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'country' => $request->country,
            'address' => $request->address,
            'city' => $request->city,
            'post_code' => $request->post_code,
            'phone' => $request->phone,
            'code' => $request->code,
        ]);

        // Generate JWT token for the newly registered customer
        $token = JWTAuth::fromUser($customer);

        return response()->json(compact('customer', 'token'), 201);
    }

    // Token response helper function
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('customer')->factory()->getTTL() * 60
        ]);
    }

    public function fetch(){
        // Check if the admin is authenticated
        if (! Auth::guard('customer')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            // If authenticated, proceed
            $data = Auth::guard('customer')->user(); // Use guard to get the admin data
            return response()->json($data);
        }

    }
}
