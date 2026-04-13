<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            "travel_post_id" => $this->travel_post_id,
            "user_id" => $this->user_id,
            "comment" => $this->comment,
            "user" => new UserSimpleResource($this->user),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
