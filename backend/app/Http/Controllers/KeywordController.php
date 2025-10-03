<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;


class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Keyword::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:100']);
        return Keyword::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
