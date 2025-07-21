<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::included()->filter()->sort()->getOrPaginate();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'veterinarian_id' => 'required|exists:veterinarians,id',
            'service_id' => 'required|exists:services,id',
            'schedule_id' => 'required|exists:schedules,id',
            'trainer_id' => 'required|exists:trainers,id',
            'pet_id' => 'nullable|exists:pets,id',
        ]);

        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['user', 'veterinarian', 'service', 'schedule', 'trainer', 'pet'])->findOrFail($id);
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'date' => 'sometimes|date',
            'description' => 'nullable|string',
            'user_id' => 'sometimes|exists:users,id',
            'veterinarian_id' => 'sometimes|exists:veterinarians,id',
            'service_id' => 'sometimes|exists:services,id',
            'schedule_id' => 'sometimes|exists:schedules,id',
            'trainer_id' => 'sometimes|exists:trainers,id',
            'pet_id' => 'nullable|exists:pets,id',
        ]);

        $appointment->update($request->all());
        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted']);
    }
}
