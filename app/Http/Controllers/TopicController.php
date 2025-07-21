<?php
// app/Http/Controllers/TopicController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::included()->filter()->sort()->getOrPaginate();
        return response()->json($topics);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'creation_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'forum_id' => 'required|exists:forums,id',
        ]);

        $topic = Topic::create($request->all());
        return response()->json($topic, 201);
    }

    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        return response()->json($topic);
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'sometimes|max:255',
            'description' => 'sometimes|nullable',
            'creation_date' => 'sometimes|date',
            'user_id' => 'sometimes|exists:users,id',
            'forum_id' => 'sometimes|exists:forums,id',
        ]);

        $topic->update($request->all());
        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return response()->json(['message' => 'Topic deleted successfully']);
    }
}
