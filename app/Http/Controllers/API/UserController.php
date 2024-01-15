<?php

namespace App\Http\Controllers\API;

use App\Actions\JsonResponseFormat;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){
        
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return JsonResponseFormat::handle(data: $validator->errors() , message: 'Validation error', status: 400);
        }

        if(!Auth::attempt($validator->validated()))
        {
            return JsonResponseFormat::handle(message: 'Invalid Login Credentials', status: 400);
        }

        $token = Auth::user()->createToken('authToken')->accessToken;

        return JsonResponseFormat::handle(['api_access_token' => $token]);
    }
}
