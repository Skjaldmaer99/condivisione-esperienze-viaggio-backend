<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\TravelPostController;
use App\Http\Controllers\UserController;
use App\Models\TravelPost;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Rotte pubbliche, accessibili a tutti
Route::get('/posts/top-liked', [TravelPostController::class, 'topLiked']);
Route::get('/posts/top-bookmarked', [TravelPostController::class, 'topBookmarked']);
Route::get('/users/top-users', [UserController::class, 'topUsers']); // tutti utenti
Route::get('/posts/bookmarks', [BookmarkController::class, 'list'])->middleware(['auth:sanctum']);
Route::get('/posts', [TravelPostController::class, 'index']);      // lista post
Route::get('/posts/{id}', [TravelPostController::class, 'show']); // singolo post
Route::get('/users', [UserController::class, 'index']); // tutti utenti

// il middleware si occupa di verificare l'autenticazione
Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    Route::middleware(['verified'])->group(function () {
        
        Route::get('/user', [AuthController::class, 'user']); // questo 'user' lo andiamo poi a scrivere all'interno della classe

        Route::post('/posts', [TravelPostController::class, 'store']);
        Route::put('/posts/{id}', [TravelPostController::class, 'update']);   // aggiorna
        Route::delete('/posts/{id}', [TravelPostController::class, 'destroy']); // cancella
        
        Route::post('/posts/{id}/comments', [CommentController::class, 'store']); 
        Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']); 

        Route::post('/posts/{post}/likes/toggle', [LikeController::class, 'toggle']);
        Route::post('/posts/{post}/bookmarks/toggle', [BookmarkController::class, 'toggle']);
    });
});

