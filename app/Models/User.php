<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/* #[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])] */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'img'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relazione con travel posts
    public function travelPosts()
    {
        return $this->hasMany(TravelPost::class);
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
