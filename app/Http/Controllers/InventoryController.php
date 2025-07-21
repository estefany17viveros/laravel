<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::included()->filter()->sort()->getOrPaginate();
        return response()->json($inventories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity_available' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        $inventory = Inventory::create($request->all());
        return response()->json($inventory, 201);
    }

    public function show($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        return response()->json($inventory);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity_available' => 'sometimes|required|integer|min:0',
            'product_id' => 'sometimes|required|exists:products,id',
        ]);

        $inventory->update($request->all());
        return response()->json($inventory);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(['message' => 'Inventory deleted']);
    }
}
