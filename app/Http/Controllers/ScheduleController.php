<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::included()->filter()->sort()->getOrPaginate();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'hour' => 'required|integer|min:0|max:23',
            'location' => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id',
        ]);

        $schedule = Schedule::create($request->all());
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $schedule = Schedule::with('service')->findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'date' => 'sometimes|date',
            'hour' => 'sometimes|integer|min:0|max:23',
            'location' => 'sometimes|string|max:255',
            'service_id' => 'nullable|exists:services,id',
        ]);

        $schedule->update($request->all());
        return response()->json($schedule);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted']);
    }
}
