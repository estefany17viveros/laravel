<?php

namespace App\Http\Controllers;

use App\Models\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        // Consulta con relaciones incluidas, filtrado, ordenamiento y paginación
        $veterinarians = Veterinarian::included()->filter()->sort()->getOrPaginate();

        return response()->json($veterinarians);
    }

    /**
     * Store a newly created veterinarian.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:100',
            'email'         => 'required|email|unique:veterinarians,email',
            'phone'         => 'required|max:20',
            'specialty'     => 'nullable|max:100',
            'experience'    => 'nullable|max:100',
            'qualifications'=> 'nullable|max:255',
            'biography'     => 'nullable',
            'user_id'       => 'required|exists:users,id',
            'shelter_id'    => 'required|exists:shelters,id',
        ]);

        $veterinarian = Veterinarian::create($request->all());

        return response()->json($veterinarian, 201);
    }

    /**
     * Display the specified veterinarian.
     */
    public function show($id)
    {
        $veterinarian = Veterinarian::findOrFail($id);
        return response()->json($veterinarian);
    }

    /**
     * Update the specified veterinarian.
     */
    public function update(Request $request, Veterinarian $veterinarian)
    {
        $request->validate([
            'name'          => 'sometimes|max:100',
            'email'         => 'sometimes|email|unique:veterinarians,email,' . $veterinarian->id,
            'phone'         => 'sometimes|max:20',
            'specialty'     => 'nullable|max:100',
            'experience'    => 'nullable|max:100',
            'qualifications'=> 'nullable|max:255',
            'biography'     => 'nullable',
            'user_id'       => 'sometimes|exists:users,id',
            'shelter_id'    => 'sometimes|exists:shelters,id',
        ]);

        $veterinarian->update($request->all());

        return response()->json($veterinarian);
    }

    /**
     * Remove the specified veterinarian.
     */
    public function destroy(Veterinarian $veterinarian)
    {
        $veterinarian->delete();
        return response()->json(['message' => 'Veterinarian deleted successfully']);
    }
}
