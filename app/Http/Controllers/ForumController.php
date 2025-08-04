<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $query = Forum::query();
        
        // Filtro adicional por rango de fechas
        if (request('start_date') && request('end_date')) {
            $query->whereBetween('date', [
                request('start_date'),
                request('end_date')
            ]);
        }
        
        // Filtro por usuario si está presente
        if (request('user_filter')) {
            $query->where('user_id', request('user_filter'));
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:forums,name',
            'description' => 'required|string',
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $forum = Forum::create($validated);
        return response()->json($forum, 201);
    }

    public function show($id)
    {
        $forum = Forum::included()->findOrFail($id);
        return response()->json($forum);
    }

    public function update(Request $request, Forum $forum)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:forums,name,'.$forum->id,
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $forum->update($validated);
        return response()->json($forum);
    }

    public function destroy(Forum $forum)
    {
        $forum->delete();
        return response()->json(null, 204);
    }
}