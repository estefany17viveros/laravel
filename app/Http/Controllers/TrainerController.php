<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::included()->filter()->sort()->getOrPaginate();
        return response()->json($trainers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|max:100',
            'specialty'  => 'required|max:100',
            'experience' => 'required|integer|min:0',
            'rating'     => 'required|numeric|between:0,5',
            'phone'      => 'required|digits_between:7,15',
            'email'      => 'required|email|unique:trainers,email',
            'biography'  => 'nullable|string',
            'user_id'    => 'required|exists:users,id',
        ]);

        $trainer = Trainer::create($request->all());
        return response()->json($trainer, 201);
    }

    public function show($id)
    {
        $trainer = Trainer::with(['user'])->findOrFail($id);
        return response()->json($trainer);
    }

    public function update(Request $request, Trainer $trainer)
    {
        $request->validate([
            'name'       => 'sometimes|max:100',
            'specialty'  => 'sometimes|max:100',
            'experience' => 'sometimes|integer|min:0',
            'rating'     => 'sometimes|numeric|between:0,5',
            'phone'      => 'sometimes|digits_between:7,15',
            'email'      => 'sometimes|email|unique:trainers,email,' . $trainer->id,
            'biography'  => 'nullable|string',
            'user_id'    => 'sometimes|exists:users,id',
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
