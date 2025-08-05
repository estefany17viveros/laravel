<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::withCount(['appointments', 'services'])
            ->included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($trainers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'specialty' => 'required|string|max:100',
            'experience' => 'required|integer|min:0',
            'rating' => 'required|numeric|between:0,5',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100|unique:trainers',
            'biography' => 'nullable|string',
            'status' => 'sometimes|string|in:active,inactive,on_leave',
            'certifications' => 'nullable|string',
            'hourly_rate' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id'
        ]);

        $trainer = Trainer::create($request->all());
        return response()->json($trainer, 201);
    }

    public function show($id)
    {
        $trainer = Trainer::withCount(['appointments', 'services'])
            ->included()
            ->findOrFail($id);
            
        return response()->json($trainer);
    }

    public function update(Request $request, Trainer $trainer)
    {
        $request->validate([
            'name' => 'sometimes|string|max:100',
            'specialty' => 'sometimes|string|max:100',
            'experience' => 'sometimes|integer|min:0',
            'rating' => 'sometimes|numeric|between:0,5',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:100|unique:trainers,email,'.$trainer->id,
            'biography' => 'nullable|string',
            'status' => 'sometimes|string|in:active,inactive,on_leave',
            'certifications' => 'nullable|string',
            'hourly_rate' => 'sometimes|numeric|min:0',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $trainer->update($request->all());
        return response()->json($trainer);
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->delete();
        return response()->json(['message' => 'Trainer deleted successfully']);
    }
}