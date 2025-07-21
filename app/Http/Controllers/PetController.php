<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::included()->filter()->sort()->getOrPaginate();
        return response()->json($pets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'species' => 'required|string',
            'breed' => 'required|string',
            'size' => 'required|numeric',
            'sex' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'trainer_id' => 'required|exists:trainers,id',
            'shelter_id' => 'required|exists:shelters,id',
            'user_id' => 'required|exists:users,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
        ]);

        $pet = Pet::create($request->all());
        return response()->json($pet, 201);
    }

    public function show($id)
    {
        $pet = Pet::with(['trainer', 'shelter', 'user', 'veterinarian'])->findOrFail($id);
        return response()->json($pet);
    }

    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'age' => 'sometimes|integer',
            'species' => 'sometimes|string',
            'breed' => 'sometimes|string',
            'size' => 'sometimes|numeric',
            'sex' => 'sometimes|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'trainer_id' => 'sometimes|exists:trainers,id',
            'shelter_id' => 'sometimes|exists:shelters,id',
            'user_id' => 'sometimes|exists:users,id',
            'veterinarian_id' => 'sometimes|exists:veterinarians,id',
        ]);

        $pet->update($request->all());
        return response()->json($pet);
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(['message' => 'Pet deleted']);
    }
}
