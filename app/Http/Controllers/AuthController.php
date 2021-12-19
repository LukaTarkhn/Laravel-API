<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(): Response {
        $attributes = request()->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', Rule::unique('users','email'), 'max:255', 'min:5'],
            'password' => ['required', 'string', 'confirmed', 'max:255', 'min:5'],
        ]);

        $user = User::create($attributes);
        $token = $user->createToken($attributes['name'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(): Response {
            $attributes = request()->validate([
                'email' => ['required', 'string', 'max:255', 'min:5'],
                'password' => ['required', 'string', 'max:255', 'min:5'],
            ]);

            if (! Auth::attempt($attributes)) {
                throw ValidationException::withMessages([
                    'incorrect' => ['The provided credentials are incorrect.'],
                ]);
            }
            $user = User::where('email', $attributes['email'])->first();
            $token = $user->createToken($user->name)->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 200);
        }

    public function logout(): Response {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->tokens()->delete();
        }

        $response = [
            'message' => 'Successfully logged out',
        ];

        return response($response, 200);
    }
}
