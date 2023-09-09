<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\Tweet; // Import the Tweet model

class TweetController extends Controller{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:280',
            'media' => 'nullable|file|max:2048', // Example: Allowing file uploads up to 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('media')) {
            // Handle file upload
            $file = $request->file('media');
            $path = $file->store('media'); // Store the file in the 'media' directory
        } else {
            $path = null;
        }

        $tweet = Tweet::create([
            'text' => $request->input('text'),
            'user_id' => auth()->user()->id, // Set the user_id to the authenticated user's ID
            'media' => $path, // Set the media field as needed
        ]);

        return response()->json($tweet, 201);
    }

    public function edit(Request $request, $id)
    {
        $tweet = Tweet::findorfail($id);

        if (!$tweet) {
            return response()->json(['message' => 'Tweet not found'], 404);
        }

        // Check if the authenticated user owns the tweet
        if (auth()->user()->id !== $tweet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|max:280',
            'media' => 'nullable|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('media')) {
            // Handle file upload
            $file = $request->file('media');
            $path = $file->store('media');
        } else {
            $path = $tweet->media;
        }

        $tweet->update([
            'text' => $request->input('text'),
            'media' => $path,
        ]);

        return response()->json(['message' => 'Tweet updated successfully', 'tweet' => $tweet], 200);
    }

    public function delete($id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json(['message' => 'Tweet not found'], 404);
        }

        // Check if the authenticated user owns the tweet
        if (auth()->user()->id !== $tweet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tweet->delete();

        return response()->json(['message' => 'Tweet deleted'], 200);
    }
}