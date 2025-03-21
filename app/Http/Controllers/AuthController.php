<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

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
