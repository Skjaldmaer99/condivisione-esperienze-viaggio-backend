<?php

use App\Http\Controllers\AuthController;
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
Route::get('/posts', [TravelPostController::class, 'index']);      // lista post
Route::get('/posts/{id}', [TravelPostController::class, 'show']); // singolo post

// il middleware si occupa di verificare l'autenticazione
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['verified'])->group(function () {
        Route::get('/user', [AuthController::class, 'user']); // questo 'user' lo andiamo poi a scrivere all'interno della classe

        Route::post('/posts', [TravelPostController::class, 'store']);
        Route::put('/posts/{id}', [TravelPostController::class, 'update']);   // aggiorna
        Route::delete('/posts/{id}', [TravelPostController::class, 'destroy']); // cancella

        Route::post('/posts/{id}/comments', [CommentController::class, 'store']); 
        Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']); 

        Route::post('/posts/{id}/likes', [LikeController::class, 'store']); 
        Route::delete('/posts/{post}/likes/{like}', [LikeController::class, 'destroy']); 
        
    });
    
});

//Route::get('/posts', [TravelPostController::class, 'index']);
/* Route::apiResource('/posts', TravelPostController::class); */

