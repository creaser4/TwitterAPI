<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\FollowerController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Routes

Route::controller(UserController::class)->group(function() {
    Route::post('/login', [UserController::class, 'login']); // Login User
    Route::post('/register', [UserController::class, 'register']); // Register User
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum'); //Logout the User and delete Token
});


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('Follower')->group(function () {
        Route::post('follow/{user}', [FollowerController::class, 'follow']); // Follow a user
        Route::post('unfollow/{user}', [FollowerController::class, 'unfollow']); // Unfollow a user
        Route::get('following-tweets', [FollowerController::class, 'following']); // List of followed users' tweets
        Route::get('tweets', [FollowerController::class, 'tweets']); // List of user's own tweets
        Route::get('suggested-users', [FollowerController::class, 'suggestedUsers']); // Suggested users to follow
    });

    Route::controller(TweetController::class)->group(function() {
        Route::post('/tweets', [TweetController::class, 'store']);
        Route::put('/tweets/{id}', [TweetController::class, 'edit']);
        Route::delete('/tweets/{id}',  [TweetController::class, 'delete']);
    });
    
});