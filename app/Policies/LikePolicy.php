<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\TravelPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LikePolicy
{
    public function toggle(User $user, TravelPost $post): bool
    {
        return true; // basta essere autenticati
    }
}
