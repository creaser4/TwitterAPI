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

// Login User
Route::post('/login', [UserController::class, 'login']);
// Register User
Route::post('/register', [UserController::class, 'register']);
//Logout the User and delete Token
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


// Create a tweet
Route::post('/tweets', [TweetController::class, 'store']);
// Edit a tweet
Route::put('/tweets/{id}', [TweetController::class, 'edit']);
// Delete a tweet
Route::delete('/tweets/{id}', [TweetController::class, 'delete']);

// Protected Routes (require authentication)
Route::group(['middleware' => ['auth:sanctum']], function() {
    // Tweet Routes
    Route::post('/tweets', [TweetController::class, 'store']);
    Route::put('/tweets/{id}', [TweetController::class, 'edit']);
    Route::delete('/tweets/{id}',  [TweetController::class, 'delete']);
});

//User Profile Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('Follower')->group(function () {
        // Follow a user
        Route::post('follow/{user}', [FollowerController::class, 'follow']);

        // Unfollow a user
        Route::post('unfollow/{user}', [FollowerController::class, 'unfollow']);

        // List of followed users' tweets
        Route::get('following-tweets', [FollowerController::class, 'following']);

        // List of user's own tweets
        Route::get('tweets', [FollowerController::class, 'tweets']);

        // Suggested users to follow
        Route::get('suggested-users', [FollowerController::class, 'suggestedUsers']);

    });
});