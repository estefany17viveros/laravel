<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $query = Inventory::query();
        
       
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quantity_available' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        $inventory = Inventory::create($validated);
        return response()->json($inventory, 201);
    }

    public function show($id)
    {
        $inventory = Inventory::included()->findOrFail($id);
        return response()->json($inventory);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity_available' => 'sometimes|integer|min:0',
            'product_id' => 'sometimes|exists:products,id',
        ]);

        $inventory->update($validated);
        return response()->json($inventory);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(null, 204);
    }
}