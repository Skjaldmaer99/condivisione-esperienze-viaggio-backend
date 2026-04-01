<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $data = $request->validated();

        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data['password'])
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => "Registrazione completata con successo",
            "user" => $user
        ], 201);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenziali non valide'
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login effettuato con successo',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout() {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout effettuato con successo'
        ]);
    }

    public function logoutAll() {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout da tutti i dispositivi effettuato con successo'
        ]);
    }

    public function user() {
        return new UserResource(Auth::user());
    }
}
