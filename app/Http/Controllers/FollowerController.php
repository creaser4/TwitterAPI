<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    // Follow a user
    public function follow(User $user)
    {
        Auth::user()->following()->attach($user);

        return response()->json(['message' => 'You are now following ' . $user->name]);
    }

    // Unfollow a user
    public function unfollow(User $user)
    {
        Auth::user()->following()->detach($user);

        return response()->json(['message' => 'You have unfollowed ' . $user->name]);
    }

    // Get a list of users the authenticated user is following along with their tweets
    public function following()
    {
        $followingUsers = Auth::user()->following;
        $followingUsersWithTweets = [];

        foreach ($followingUsers as $user) {
            $followingUsersWithTweets[] = [
                'user' => $user,
                'tweets' => $user->tweets()->latest()->get(),
            ];
        }

        return response()->json($followingUsersWithTweets);
    }

    // Get a list of tweets by the authenticated user
    public function tweets()
    {
        $user = Auth::user();
        $tweets = $user->tweets()->latest()->get();

        return response()->json($tweets);
    }

    // Get a list of suggested users to follow along with their tweets
    public function suggestedUsers()
    {
        $suggestedUsers = User::where('id', '!=', Auth::id())->inRandomOrder()->limit(5)->get();
        $suggestedUsersWithTweets = [];

        foreach ($suggestedUsers as $user) {
            $suggestedUsersWithTweets[] = [
                'user' => $user,
                'tweets' => $user->tweets()->latest()->limit(5)->get(),
            ];
        }

        return response()->json($suggestedUsersWithTweets);
    }
}
