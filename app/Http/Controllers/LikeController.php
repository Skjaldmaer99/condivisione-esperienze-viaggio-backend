<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use App\Models\TravelPost;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request, $id)
    {
        try {
            $like = Like::create([
                'user_id' => $request->user()->id,
                'travel_post_id' => (int) $id,
            ]);
    
            return response()->json([
                "success" => true,
                "message" => "Like inserito correttamente",
                "data" => $like
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function destroy(TravelPost $post, Like $like)
    {
        try {
            if($like->travel_post_id !== $post->id) {
                return response()->json([
                    "success" => false,
                    "message" => "Commento non valido per questo post"
                ], 403);
            }
    
            if($like->user_id !== auth()->id()) {
                return response()->json([
                    "success" => false,
                    "message" => "Non autorizzato"
                ], 403);
            }

            $like->delete();
            return response()->json([
                    "success" => true,
                    "message" => "Like rimosso correttamente"
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                    "success" => false,
                    "message" => $e->getMessage()
                ]);
        }


    }
}
