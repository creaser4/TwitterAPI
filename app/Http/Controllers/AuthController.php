<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Generate an access token for the new user
        $accessToken = $user->createToken('MyApp')->accessToken;

        $plainTextToken = $accessToken->token;
        return response()->json([
            'user' => $user,
            'plainTextToken' => $plainTextToken], 201);
    }

    public function login(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            

            return response()->json(['token' => $token], 200);
        }

        // Authentication failed
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            // Revoke the user's personal access tokens
            $request->user()->tokens()->each(function ($token, $key) {
                $token->delete();
            });
    
            return response()->json(['message' => 'Logged out successfully'])->setStatusCode(200);
        }
    
        return response()->json(['message' => 'No user to log out'],400);
    }
}