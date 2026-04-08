<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use App\Models\TravelPost;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(TravelPost $post) {
        $like = Like::where('user_id', auth()->id())
            ->where('travel_post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        }

        $post->likes()->create([
            'user_id' => auth()->id()
        ]);

        return response()->json(['liked' => true]);
    }
}
