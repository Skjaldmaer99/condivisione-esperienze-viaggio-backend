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
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $imagePath = null;

        try {
            if($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('users', 'public');
            }
            
            $data = $request->validated();

            $user = User::create([
                "name" => $data['name'],
                "email" => $data['email'],
                "img" => $imagePath,
                "password" => Hash::make($data['password'])
            ]);

            event(new Registered($user));

            return response()->json([
                'success' => true,
                'message' => "Registrazione completata con successo",
                "user" => new UserResource($user)
            ], 201);
        } catch(\Throwable $th) {
            if($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            throw $th;
        }
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
            'user' => new UserResource($user),
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

    // restituisce l'utente autenticato
    public function user() {
        return new UserResource(Auth::user());
    }
}
