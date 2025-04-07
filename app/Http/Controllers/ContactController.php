<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Contact::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is not typically used in API controllers
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Storing the contact message in the database
        Contact::create($request->all());

        return response()->json(['message' => 'Contact message sent successfully!'], 201);
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        try {
            $contact = Contact::findOrFail($id);
    
            return response()->json([
                'message' => 'Contact message found!',
                'data' => $contact
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Contact message not found!',
                'data' => $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
