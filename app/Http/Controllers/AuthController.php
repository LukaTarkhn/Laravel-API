<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken($request->name)->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if(! $user || ! Hash::check($request->password, $user->password)) {
                 throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                 ]);
            }

            $token = $user->createToken($user->name)->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 201);
        }

    public function logout() {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out',
        ];
    }
}
