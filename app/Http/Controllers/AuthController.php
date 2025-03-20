<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function Register(Request $request)
    {
        //
        $isValidated = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users', 
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string|unique:users|regex:/^\+?\d{10,15}$/',
            'nationality' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required',
        ]);

        if ($isValidated->fails()) {
            return response()->json(['errors' => $isValidated->errors()], 422);
        }

        $validatedData = $isValidated->validated();
        $users = new User();
        $users->name = $validatedData['name'];
        $users->email = $validatedData['email'];
        $users->password = Hash::make($validatedData['password']);
        $users->phone = $validatedData['phone'];
        $users->nationality = $validatedData['nationality'];
        $users->date_of_birth = $validatedData['date_of_birth'];
        $users->gender = $validatedData['gender'];	
        $users->save();
        return response()->json(['success' => 'You have successfully registered'], 201);
    }
    public function Login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ])->validated();
    
        if (Auth::attempt($validated)) {
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
    
        return response()->json(['error' => 'Invalid credentials'], 401); 
    }
}