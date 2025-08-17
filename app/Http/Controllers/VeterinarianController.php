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
            'name'      => 'required|string|max:255',
            'address'   => 'required|string|max:500',   
            'email'     => 'required|email|unique:veterinarians,email',
            'phone'     => 'required|string|max:20',   
            'schedules'  => 'required|string|max:255',  
            'user_id'   => 'required|exists:users,id',
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
            'name'      => 'sometimes|string|max:255',
            'address'   => 'sometimes|string|max:500',
            'email'     => 'sometimes|email|unique:veterinarians,email,' . $veterinarian->id,
            'phone'     => 'sometimes|string|max:20',
            'shedules'  => 'sometimes|string|max:255', 
            'user_id'   => 'sometimes|exists:users,id',
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