<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $query = Inventory::query();
        
        // Filtro adicional para stock bajo
        if (request('low_stock')) {
            $query->whereColumn('quantity_available', '<=', 'minimum_stock');
        }
        
        // Filtro por rango de cantidades
        if (request('min_quantity') && request('max_quantity')) {
            $query->whereBetween('quantity_available', [
                request('min_quantity'),
                request('max_quantity')
            ]);
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quantity_available' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
            'minimum_stock' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:100'
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
            'minimum_stock' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:100'
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