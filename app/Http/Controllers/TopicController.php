<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
           $topics = Topic::query()
           ->included()
           ->filter()
           ->sort()
          ->getOrPaginate();
            
        return response()->json($topics);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'creation_date' => 'required|date',
            'forum_id' => 'required|exists:forums,id',
        ]);

        return response()->json($topic, 201);
    }
      public function show($id)
     {
            $topic = Topic::included()->findOrFail($id);
    
          return response()->json($topic);
     }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'creation_date' => 'sometimes|date',
            'forum_id' => 'sometimes|exists:forums,id',
        ]);

        $topic->update($request->except('tags'));
        
        if ($request->has('tags')) {
            $topic->tags()->sync($request->tags);
        }

        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        $topic->tags()->detach();
        $topic->delete();
        return response()->json(['message' => 'Topic deleted successfully']);
    }
}