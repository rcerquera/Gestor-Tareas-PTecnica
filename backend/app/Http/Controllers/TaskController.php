<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::with('keywords')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keywords' => 'array'
        ]);

        $task = Task::create(['title' => $validated['title']]);
        if (!empty($validated['keywords'])) {
            $task->keywords()->attach($validated['keywords']);
        }

        return response()->json($task->load('keywords'), 201);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->is_done = !$task->is_done;
        $task->save();

        return response()->json($task);
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
