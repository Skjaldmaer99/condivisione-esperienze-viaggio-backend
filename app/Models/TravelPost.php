<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPost extends Model
{
    protected $fillable = [
        "title",
        "location",
        "country",
        "description",
        "img",
        'user_id',
    ];

    // Relazione con utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con commenti
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relazione con likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // Relazione con bookmarks
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
