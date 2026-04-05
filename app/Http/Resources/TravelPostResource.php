<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TravelPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "location" => $this->location,
            "country" => $this->country,
            "description" => $this->description,
            "img" => $this->img ? Storage::url($this->img) : null, // cambia FILESYSTEM_DISK=local da local a public per avere il path completo
            "user_id" => $this->user_id,
            "user" => new UserResource(User::find($this->user_id)),
            "comments" => CommentResource::collection(Comment::where("travel_post_id", $this->id)->get()),
            "likes" => Like::where("travel_post_id", $this->id)->get(),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
        /* return [
            "id" => $this->id,
            "title" => $this->title,
            "location" => $this->location,
            "country" => $this->country,
            "description" => $this->description,
            "img" => $this->img ? Storage::url($this->img) : null,

            "user_id" => $this->user_id,

            // ✅ usa la relazione, NON User::find()
            "user" => new UserResource($this->user),

            // ✅ usa la relazione, NON where()
            "comments" => CommentResource::collection($this->comments),

            // ✅ meglio così (oppure crea LikeResource)
            "likes" => $this->likes,

            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ]; */
    }
}
