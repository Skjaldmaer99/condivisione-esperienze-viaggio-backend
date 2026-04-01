<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
/*     public function create()
    {
        $user->
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $request->validated();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                "success" => false,
                "message" => "Utente non esistente"
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => $user
        ]);
    }
}
