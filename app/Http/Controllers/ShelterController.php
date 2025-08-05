<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shelter;

class ShelterController extends Controller
{
    public function index()
    {
        $shelters = Shelter::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($shelters);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'responsible' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:shelters,email',
            'address' => 'required|string|max:500',
            'user_id' => 'required|exists:users,id',
        ]);

        $shelter = Shelter::create($request->all());
        return response()->json($shelter, 201);
    }

    public function show($id)
    {
        $shelter = Shelter::included()->findOrFail($id);
        return response()->json($shelter);
    }

    public function update(Request $request, Shelter $shelter)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'responsible' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:shelters,email,'.$shelter->id,
            'address' => 'sometimes|string|max:500',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $shelter->update($request->all());
        return response()->json($shelter);
    }

    public function destroy(Shelter $shelter)
    {
        $shelter->delete();
        return response()->json(['message' => 'Shelter deleted successfully']);
    }
}