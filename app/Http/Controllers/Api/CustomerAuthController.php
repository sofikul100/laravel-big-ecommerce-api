<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
class CustomerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:customers',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = Customer::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Customer successfully registered',
            'user' => $user
        ], 201);
    }



    public function login(Request $request){
        $request->validate([
           'email'=>'required|email',
           'password'=>'required|min:6'
        ]);
        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard('customer_api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credencials..'], 401);
        }

        return $this->respondWithToken($token);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('customer_api')->factory()->getTTL() * 60
        ]);
    }





    public function me()
    {
        return response()->json(auth('customer_api')->user());
    }



    
    public function logout()
    {
        auth('customer_api')->logout();

        return response()->json(['message' => 'Successfully logged out done']);
    }











}
