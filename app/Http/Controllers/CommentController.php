<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\TravelPost;

class CommentController extends Controller
{
/*     public function index()
    {
        return ::collection(TravelPost::paginate(6));
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $comment = Comment::create([
                'user_id' => $request->user()->id,
                'travel_post_id' => (int) $id,
                /* 'travel_post_id' => $post->id, */
                'comment' => $data['comment']
            ]);

            return response()->json([
                "success" => true,
                "message" => "Commento creato con successo",
                "data" => $comment
            ], 201);
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
    public function destroy(TravelPost $post, Comment $comment)
    {
        //$comment = Comment::where("id", $id)->delete();
        if($comment->travel_post_id !== $post->id) {
            return response()->json([
                "success" => false,
                "message" => "Commento non valido per questo post"
            ], 403);
        }

        if($comment->user_id !== auth()->id()) {
            return response()->json([
                "success" => false,
                "message" => "Non autorizzato"
            ], 403); 
        }

        $comment->delete();
        return response()->json([
                "success" => true,
                "message" => "Commento eliminato con successo"
        ], 200);
    }
}
