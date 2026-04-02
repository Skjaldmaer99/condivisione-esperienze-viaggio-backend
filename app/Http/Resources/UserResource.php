<?php

namespace App\Http\Resources;

use App\Models\Comment;
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
            'name' => $this->name,
            'email' => $this->email,
            //'img' => $this->img ? Storage::url($this->img) : null, // cambia FILESYSTEM_DISK=local da local a public per avere il path completo
            'img' => $this->img ? Storage::disk('public')->url($this->img) : null,
            'comments' => Comment::find($this->user_id),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
