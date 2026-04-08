<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate(6));
    }
    
    public function topUsers()
    {
        $users = User::withCount('travelPosts')
        ->orderBy('travel_posts_count', 'desc')
        ->take(4)
        ->get();

        return UserResource::collection($users);
    }


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
        /* $user = User::with(['posts', 'comments'])->find($id); */
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

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = User::find($id);
            /* $user = User::with(['posts', 'comments'])->find($id); */

            $oldImagePath = $user->img;
            $newImagePath = $oldImagePath;

            if($user->id !== auth()->id()) {
                return response()->json([
                    "success" => false,
                    "message" => "Non autorizzato",
                ]);
            }

            $data = $request->validated();

            if($request->hasFile('img')) {
                $newImagePath = $request->file('img')->store('users', 'public');
            }

            if($newImagePath) {
                $payload['img'] = $newImagePath;
            }

            if($request->hasFile('img') && $newImagePath && $newImagePath !== $oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $data['img'] = $newImagePath;
                
            $user->update($data);

            return response()->json([
                "success" => true,
                "message" => "Utente modificato con successo",
                "data" => new UserResource($user)
            ]);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }

    }
}
