<?php

namespace App\Http\Resources;

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
            "user" => new UserSimpleResource($this->user),
            "comments" => CommentResource::collection($this->comments),
            "likes" => $this->likes,
            "bookmarks" => $this->bookmarks,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
