<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Http\Resources\TravelPostResource;
use App\Models\TravelPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelPostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {    
        $posts = TravelPost::with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->when($request->search, function ($q, $search) {
            $q->where(function ($q2) use ($search) {
            $q2->whereRaw('LOWER(location) LIKE ?', ['%' . strtolower($search) . '%'])
            ->orWhereRaw('LOWER(country) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(6);

        return TravelPostResource::collection($posts);
    }

    public function topLiked(Request $request)
    {    
        $posts = TravelPost::withCount('likes')
        ->orderBy('likes_count', 'desc')
        ->take(3)
        ->get();

        return TravelPostResource::collection($posts);
    }

    public function topBookmarked(Request $request)
    {    
        $posts = TravelPost::withCount('bookmarks')
        ->orderBy('bookmarks_count', 'desc')
        ->take(3)
        ->get();

        return TravelPostResource::collection($posts);
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

        if (!$post) {
            return response()->json([
                "success" => false,
                "message" => "Post non trovato"
            ], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json([
                "success" => false,
                "message" => "Non autorizzato"
            ], 403);
        }

        $imagePath = $post->img;

        $post->delete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json([
            "success" => true,
            "message" => "Post eliminato correttamente"
        ], 200);
    }
}
