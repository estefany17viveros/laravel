<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use Illuminate\Http\Request;

class AdoptionController extends Controller
{
    public function index()
    {
        $query = Adoption::query();

        // Filtro adicional específico para Adoption si es necesario
        if (request('status_filter')) {
            $query->where('status', request('status_filter'));
        }

        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_date' => 'required|date',
            'status' => 'required|string',
            'comments' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
            'requestt_id' => 'required|exists:requestts,id',
            'shelter_id' => 'required|exists:shelters,id',
        ]);

        $adoption = Adoption::create($validated);
        return response()->json($adoption, 201);
    }

    public function show($id)
    {
        $adoption = Adoption::included()->findOrFail($id);
        return response()->json($adoption);
    }

    public function update(Request $request, Adoption $adoption)
    {
        $validated = $request->validate([
            'application_date' => 'sometimes|date',
            'status' => 'sometimes|string',
            'comments' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'pet_id' => 'sometimes|exists:pets,id',
            'requestt_id' => 'sometimes|exists:requestts,id',
            'shelter_id' => 'sometimes|exists:shelters,id',
        ]);

        $adoption->update($validated);
        return response()->json($adoption);
    }

    public function destroy(Adoption $adoption)
    {
        $adoption->delete();
        return response()->json(null, 204);
    }
}