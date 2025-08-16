<?php

namespace App\Http\Controllers;

use App\Models\Veterinary;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    public function index()
    {
        $veterinarians = Veterinary::query()
            ->included()
            ->filter()
            ->sort()
            ->getOrPaginate();

        return response()->json($veterinarians);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:veterinarians',
            'phone' => 'required|string|max:20',
            'specialty' => 'required|string|max:100',
            'experience' => 'required|integer|min:0',
            'qualifications' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'license_number' => 'required|string|max:50|unique:veterinarians',
            'consultation_fee' => 'required|numeric|min:0',
            'availability' => 'required|string|in:full-time,part-time,on-call',
            'user_id' => 'required|exists:users,id',
            'shelter_id' => 'required|exists:shelters,id'
        ]);

        $veterinarian = Veterinary::create($request->all());
        return response()->json($veterinarian, 201);
    }

    public function show($id)
    {
        $veterinarian = Veterinary::query()
            ->included()
            ->findOrFail($id);

        return response()->json($veterinarian);
    }

    public function update(Request $request, Veterinary $veterinarian)
    {
        $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100|unique:veterinarians,email,'.$veterinarian->id,
            'phone' => 'sometimes|string|max:20',
            'specialty' => 'sometimes|string|max:100',
            'experience' => 'sometimes|integer|min:0',
            'qualifications' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'license_number' => 'sometimes|string|max:50|unique:veterinarians,license_number,'.$veterinarian->id,
            'consultation_fee' => 'sometimes|numeric|min:0',
            'availability' => 'sometimes|string|in:full-time,part-time,on-call',
            'user_id' => 'sometimes|exists:users,id',
            'shelter_id' => 'sometimes|exists:shelters,id'
        ]);

        $veterinarian->update($request->all());
        return response()->json($veterinarian);
    }

    public function destroy(Veterinary $veterinarian)
    {
        $veterinarian->delete();
        return response()->json(['message' => 'Veterinarian deleted successfully']);
    }
}