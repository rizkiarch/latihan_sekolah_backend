<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => "required|max:255",
            'email' => "required|email|unique:users",
            'password' => "required|confirmed"
        ]);

        $user = User::create($data);

        $token = $user->createToken($request->name);

        return response()->json([
            'status' => 200,
            'message' => "success created account",
            'result' => 'ok',
            "user" => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => "required|email|exists:users",
            'password' => "required"
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'errors' => [
                    'email' => ["The provided credentials are incorrect."]
                ]
            ];
        }

        $token = $user->createToken($user->name);

        return response()->json([
            'status' => 200,
            'message' => "success login account",
            'result' => 'ok',
            "user" => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => "You are Logout",
            'result' => 'ok',
        ]);
    }
}
