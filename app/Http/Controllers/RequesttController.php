<?php

namespace App\Http\Controllers;

use App\Models\Requestt;
use Illuminate\Http\Request;

class RequesttController extends Controller
{
    public function index()
    {
        $requestts = Requestt::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($requestts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'priority' => 'required|integer|min:1',
            'solicitation_status' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'adoption_id' => 'required|exists:adoptions,id',
        ]);

        $requestt = Requestt::create($request->all());
        return response()->json($requestt, 201);
    }

    public function show($id)
    {
        $requestt = Requestt::included()->findOrFail($id);
        return response()->json($requestt);
    }

    public function update(Request $request, Requestt $requestt)
    {
        $request->validate([
            'date' => 'sometimes|date',
            'priority' => 'sometimes|integer|min:1',
            'solicitation_status' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'adoption_id' => 'sometimes|exists:adoptions,id',
            
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