<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Http\Resources\TravelPostResource;
use App\Models\TravelPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $imagePath = null;

        try {
            if($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('posts', 'public');
            }

            $data = $request->validated();
    
            $post = TravelPost::create([
                "title" => $data['title'],
                "location" => $data['location'],
                "country" => $data['country'],
                "description" => $data['description'],
                "img" => $imagePath,
                "user_id" => $request->user()->id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Post creato con successo',
                'data' => new TravelPostResource($post)
            ], 201);
        } catch (\Throwable $th) {
            if($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            
            throw $th;
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
                "data" => new TravelPostResource($post)
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
            $post = TravelPost::find($id);

            $oldImagePath = $post->img;
            $newImagePath = $oldImagePath;

            if($post->user_id !== auth()->id()) {
                return response()->json([
                    "success" => false,
                    "message" => "Non autorizzato",
                ]);
            }

            $data = $request->validated();

            if($request->hasFile('img')) {
                $newImagePath = $request->file('img')->store('posts', 'public');
            }

            if($newImagePath) {
                $payload['img'] = $newImagePath;
            }

            if($request->hasFile('img') && $newImagePath && $newImagePath !== $oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }
            /* if($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            } */

            $data['img'] = $newImagePath;
                
            $post->update($data);

            return response()->json([
                "success" => true,
                "message" => "Post modificato con successo",
                "data" => new TravelPostResource($post)
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
        $imagePath = $post->img;
        
        if($post->user_id !== auth()->id()) {
            return response()->json([
                "success" => false,
                "message" => "Non autorizzato"
            ]);
        }

        $post->delete();
        if($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json([
            "success" => true,
            "message" => "Post eliminato correttamente"
        ], 204);
    }
}
