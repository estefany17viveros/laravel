<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::included()->filter()->sort()->getOrPaginate();
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|date',
            'description' => 'nullable|string',
            'veterinarian_id' => 'nullable|exists:veterinarians,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'requestt_id' => 'nullable|exists:requestts,id',
        ]);

        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    public function show($id)
    {
        $service = Service::with(['veterinarian', 'trainer', 'requestt'])->findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|date',
            'description' => 'nullable|string',
            'veterinarian_id' => 'nullable|exists:veterinarians,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'requestt_id' => 'nullable|exists:requestts,id',
        ]);

        $service->update($request->all());
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service deleted']);
    }
}
