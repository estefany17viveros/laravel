<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use Illuminate\Http\Request;

class AdoptionController extends Controller
{
    public function index()
    {
        $adoptions = Adoption::included()->filter()->sort()->getOrPaginate();
        return response()->json($adoptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_date' => 'required|date',
            'status' => 'required|string',
            'comments' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'requestt_id' => 'required|exists:requestts,id',
            'shelter_id' => 'required|exists:shelters,id',
        ]);

        $adoption = Adoption::create($request->all());
        return response()->json($adoption, 201);
    }

    public function show($id)
    {
        $adoption = Adoption::with(['user', 'pet', 'requestt', 'shelter'])->findOrFail($id);
        return response()->json($adoption);
    }

    public function update(Request $request, Adoption $adoption)
    {
        $request->validate([
            'application_date' => 'sometimes|date',
            'status' => 'sometimes|string',
            'comments' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'pet_id' => 'sometimes|exists:pets,id',
            'requestt_id' => 'sometimes|exists:requestts,id',
            'shelter_id' => 'sometimes|exists:shelters,id',
        ]);

        $adoption->update($request->all());
        return response()->json($adoption);
    }

    public function destroy(Adoption $adoption)
    {
        $adoption->delete();
        return response()->json(['message' => 'Adoption deleted']);
    }
}
