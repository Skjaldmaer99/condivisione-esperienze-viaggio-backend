<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Like;
use App\Models\TravelPost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'img' => $this->img ? Storage::disk('public')->url($this->img) : null,
            //"likes" => Like::where("user_id", $this->id)->get(),
            //"comments" => CommentResource::collection(Comment::where("user_id", $this->id)->get()),
            /* "comments" => CommentResource::collection($this->comments), */
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
