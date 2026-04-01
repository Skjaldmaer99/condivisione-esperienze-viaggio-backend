<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Http\Resources\TravelPostResource;
use App\Models\TravelPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TravelPostResource::collection(TravelPost::paginate(6));
    }


    /**
     * Crea un nuovo post
     */
    public function store(StoreTravelPostRequest $request)
    {
        try {
            $data = $request->validated();
    
            $post = TravelPost::create([
                "title" => $data['title'],
                "location" => $data['location'],
                "country" => $data['country'],
                "description" => $data['description'],
                "user_id" => $request->user()->id
                /* "user_id" => Auth::id() */
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Post creato con successo',
                'data' => $post
            ], 201);
        } catch (\Exception $e) {
            // intercetta TUTTI gli errori e li trasforma in JSON
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la creazione del post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * visualizza il dettaglio di un post
     */
    public function show(string $id)
    {
        try {
            $post = TravelPost::find($id);
    
            return response()->json([
                "success" => true,
                "message" => "Post trovato con successo",
                "data" => $post
            ]);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelPostRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $post = TravelPost::find($id);

            if($post->user_id !== auth()->id()) {
                return response()->json([
                    "success" => false,
                    "message" => "Non autorizzato",
                ]);
            }

            $post->update($data);

            return response()->json([
                "success" => true,
                "message" => "Post modificato con successo",
                "data" => $post
            ]);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = TravelPost::find($id);
        
        if($post->user_id !== auth()->id()) {
            return response()->json([
                "success" => false,
                "message" => "Non autorizzato"
            ]);
        }

        $post->delete();

        return response()->json([
            "success" => true,
            "message" => "Post eliminato correttamente"
        ]);
    }
}
