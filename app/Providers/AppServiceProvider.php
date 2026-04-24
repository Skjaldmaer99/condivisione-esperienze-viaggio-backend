<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\TravelPost;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\LikePolicy;
use App\Policies\TravelPostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(TravelPost::class, TravelPostPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Like::class, LikePolicy::class);
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $frontendUrl = Config::get('app.frontend_url');
            return "{$frontendUrl}/reset-password?token={$token}&email=". urlencode($user->email);
        });
    }
    
}
