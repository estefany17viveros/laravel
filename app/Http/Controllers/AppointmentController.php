<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $query = Appointment::query();
        
        if (request('start_date') && request('end_date')) {
            $query->whereBetween('date', [
                request('start_date'),
                request('end_date')
            ]);
        }
        
        // Filtro por estado si está presente
        if (request('status_filter')) {
            $query->where('status', request('status_filter'));
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'trainer_id' => 'required|exists:trainers,id',
        ]);

        $appointment = Appointment::create($validated);
        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::included()->findOrFail($id);
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'date' => 'sometimes|date',
            'description' => 'nullable|string|max:500',
            'veterinarian_id' => 'sometimes|exists:veterinarians,id',
            'trainer_id' => 'sometimes|exists:trainers,id',
        ]);

        $appointment->update($validated);
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(null, 204);
    }
}