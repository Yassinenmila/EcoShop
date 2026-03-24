<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user=User::where('email',$request->email)->first();

        if(!$user||!Hash::check($request->password, $user->password)){

            throw ValidationException::withMessages([
                'email'=>['Les informations sont incorrectes']
            ]);
        }
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
            ],
            'token'=>$token,
            'message'=>'connection reussie'
        ],200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=> 'deconnection reussie'
        ],200);
    }

    public function signup(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'message' => 'Inscription réussie'
        ], 201);
    }
}
