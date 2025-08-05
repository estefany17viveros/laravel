<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;

class AnswerController extends Controller
{
    public function index()
    {
        $query = Answer::query();
        
        if (request('topic_filter')) {
            $query->where('topic_id', request('topic_filter'));
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'creation_date' => 'required|date',
            'topic_id' => 'required|exists:topics,id',
            'users_id' => 'required|exists:users,id',
        ]);

        $answer = Answer::create($validated);
        return response()->json($answer, 201);
    }

    public function show($id)
    {
        $answer = Answer::included()->findOrFail($id);
        return response()->json($answer);
    }

    public function update(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'content' => 'sometimes|string',
            'creation_date' => 'sometimes|date',
            'topic_id' => 'sometimes|exists:topics,id',
            'users_id' => 'sometimes|exists:users,id',
        ]);

        $answer->update($validated);
        return response()->json($answer);
    }

    public function destroy(Answer $answer)
    {
        $answer->delete();
        return response()->json(null, 204);
    }
}