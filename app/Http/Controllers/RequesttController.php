<?php

namespace App\Http\Controllers;

use App\Models\Requestt;
use Illuminate\Http\Request;

class RequesttController extends Controller
{
    public function index()
    {
        $requestts = Requestt::included()->filter()->sort()->getOrPaginate();
        return response()->json($requestts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'priority' => 'required|integer|min:1',
            'solicitation_status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'shelter_id' => 'nullable|exists:shelters,id',
            'services_id' => 'required|exists:services,id',
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $requestt = Requestt::create($request->all());
        return response()->json($requestt, 201);
    }

    public function show($id)
    {
        $requestt = Requestt::with(['user', 'shelter', 'service', 'appointment'])->findOrFail($id);
        return response()->json($requestt);
    }

    public function update(Request $request, Requestt $requestt)
    {
        $request->validate([
            'date' => 'sometimes|date',
            'priority' => 'sometimes|integer|min:1',
            'solicitation_status' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'shelter_id' => 'nullable|exists:shelters,id',
            'services_id' => 'sometimes|exists:services,id',
            'appointment_id' => 'sometimes|exists:appointments,id',
        ]);

        $requestt->update($request->all());
        return response()->json($requestt);
    }

    public function destroy(Requestt $requestt)
    {
        $requestt->delete();
        return response()->json(['message' => 'Request deleted']);
    }
}
