<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $isValidated = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($isValidated->fails()) {
            return response()->json(['errors' => $isValidated->errors()], 422);
        }

        $validatedData = $isValidated->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('user_' . $user->email)->plainTextToken;

        return response()->json([
            'success' => 'You have successfully registered',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function googleLogin(Request $request)
    {
        $idToken = $request->json('idToken');

        if (!$idToken) {
            return response()->json(['error' => 'ID token is required'], 400);
        }

        $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

        try {
            $payload = $client->verifyIdToken($idToken);

            if (!$payload) {
                return response()->json(['error' => 'Invalid ID token'], 401);
            }

            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];
            $profilePicture = $payload['picture'] ?? null;

            $user = User::where('google_id', $googleId)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(Str::random(16)),
                    'profile_picture' => $profilePicture,
                ]);
            }

            $token = $user->createToken('google_login_token')->plainTextToken;

            return response()->json(['name' => $user->name, 'token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function Login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 500);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'token' => $token,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $isValidated = Validator::make($request->all(), [
            'phone' => 'string|unique:users|regex:/^\+?\d{10,15}$/',
            'nationality' => 'string|max:100',
            'date_of_birth' => 'date|before:today',
            'gender' => 'string',
            'role' => 'string',
        ]);

        if ($isValidated->fails()) {
            return response()->json(['errors' => $isValidated->errors()], 422);
        }

        $user->update($isValidated->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
