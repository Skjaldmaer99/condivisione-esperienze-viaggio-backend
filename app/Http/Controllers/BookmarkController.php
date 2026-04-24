<?php

namespace App\Http\Controllers;

use App\Http\Resources\TravelPostResource;
use App\Models\Bookmark;
use App\Models\TravelPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    use AuthorizesRequests;
    
    public function list()
    {
        try {
            $userId = auth()->id();
    
            $posts = TravelPost::with(['likes', 'comments', 'user'])
            ->whereHas('bookmarks', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

            return response()->json([
                'success' => true,
                'data' => TravelPostResource::collection($posts)
            ]);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function toggle(TravelPost $post) {
        $bookmark = Bookmark::where('user_id', auth()->id())
            ->where('travel_post_id', $post->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false]);
        }

        $post->bookmarks()->create([
            'user_id' => auth()->id()
        ]);

        return response()->json(['bookmarked' => true]);
    }
}
