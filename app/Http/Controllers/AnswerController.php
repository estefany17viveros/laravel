<?php

// app/Http/Controllers/AnswerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::included()->filter()->sort()->getOrPaginate();
        return response()->json($answers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'creation_date' => 'required|date',
            'topic_id' => 'required|exists:topics,id',
            'users_id' => 'required|exists:users,id',
        ]);

        $answer = Answer::create($request->all());
        return response()->json($answer, 201);
    }

    public function show($id)
    {
        $answer = Answer::findOrFail($id);
        return response()->json($answer);
    }

    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'content' => 'sometimes|string',
            'creation_date' => 'sometimes|date',
            'topic_id' => 'sometimes|exists:topics,id',
            'users_id' => 'sometimes|exists:users,id',
        ]);

        $answer->update($request->all());
        return response()->json($answer);
    }

    public function destroy(Answer $answer)
    {
        $answer->delete();
        return response()->json(['message' => 'Answer deleted']);
    }
}
