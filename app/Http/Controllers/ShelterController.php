<?php

// app/Http/Controllers/ShelterController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shelter;

class ShelterController extends Controller
{
    public function index()
    {
        return response()->json(Shelter::included()->filter()->sort()->getOrPaginate());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'responsible' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $shelter = Shelter::create($request->all());
        return response()->json($shelter, 201);
    }

    public function show($id)
    {
        $shelter = Shelter::findOrFail($id);
        return response()->json($shelter);
    }

    public function update(Request $request, Shelter $shelter)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'responsible' => 'sometimes|string',
            'email' => 'sometimes|email',
            'address' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $shelter->update($request->all());
        return response()->json($shelter);
    }

    public function destroy(Shelter $shelter)
    {
        $shelter->delete();
        return response()->json(['message' => 'Shelter deleted']);
    }
}
