<?php
namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the works.
     */
    public function index()
    {
        return response()->json(Work::all());
    }

    /**
     * Store a newly created work in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:work|max:255',
            'description' => 'required',
            'location' => 'required',
            'type' => 'required|in:Online,In-Person',
            'category' => 'required',
            'status' => 'in:Open,Closed',
            'salary' => 'required|integer',
            'email' => 'required|email|unique:work',
        ]);

        $work = Work::create($request->all());
        return response()->json($work, 201);
    }

    /**
     * Display the specified work.
     */
    public function show(Work $work)
    {
        return response()->json($work);
    }

    /**
     * Update the specified work.
     */
    public function update(Request $request, Work $work)
    {
        $work->update($request->all());
        return response()->json($work);
    }

    /**
     * Remove the specified work from storage.
     */
    public function destroy(Work $work)
    {
        $work->delete();
        return response()->json(null, 204);
    }
}