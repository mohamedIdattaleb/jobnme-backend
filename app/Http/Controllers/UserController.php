<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isValidated = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string|unique:users|regex:/^\+?\d{10,15}$/',
            'nationality' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female,other',
        ]);

        if ($isValidated->fails()) {
            return response()->json(['errors' => $isValidated->errors()], 400);
        }

        $isValidateData = $isValidated->validated();

        $user = new User();
        $user->name = $isValidateData['name'];
        $user->email = $isValidateData['email'];
        $user->password = Hash::make($isValidateData['password']);
        $user->phone = $isValidateData['phone'];
        $user->nationality = $isValidateData['nationality'];
        $user->date_of_birth = $isValidateData['date_of_birth'];
        $user->gender = $isValidateData['gender'];
        $user->save();

        return response()->json(['success' => 'You have successfully registered'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $isValidated = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:users,name,' . $id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|confirmed|min:6',
            'phone' => 'sometimes|string|unique:users,phone,' . $id . '|regex:/^\+?\d{10,15}$/',
            'nationality' => 'sometimes|string|max:100',
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|string|in:male,female,other',
        ]);

        if ($isValidated->fails()) {
            return response()->json(['errors' => $isValidated->errors()], 400);
        }

        $user->update($isValidated->validated());

        return response()->json(['success' => 'User updated successfully', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['success' => 'User deleted successfully'], 200);
    }
}
