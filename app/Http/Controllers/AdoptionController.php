<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdoptionService;

class AdoptionController extends Controller
{
    protected $adoptionService;

    public function __construct(AdoptionService $adoptionService)
    {
        $this->adoptionService = $adoptionService;
    }

    public function index()
    {
        return response()->json($this->adoptionService->getAll());
    }

    public function store(Request $request)
    {
        return response()->json($this->adoptionService->create($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json($this->adoptionService->getById($id));
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->adoptionService->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->adoptionService->delete($id);
        return response()->json(['message' => 'Adopción eliminada correctamente']);
    }
}