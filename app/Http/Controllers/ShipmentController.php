<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($shipments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'shipping_method' => 'required|string|max:100',
            'tracking_number' => 'nullable|string|max:50|unique:shipments',
            'estimated_delivery' => 'nullable|date',
            'shipped_at' => 'nullable|date',
            'order_id' => 'required|exists:orders,id',
        ]);

        $shipment = Shipment::create($request->all());
        return response()->json($shipment, 201);
    }

    public function show($id)
    {
        $shipment = Shipment::included()->findOrFail($id);
        return response()->json($shipment);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipping_address' => 'sometimes|string|max:500',
            'cost' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,processing,shipped,delivered,cancelled',
            'shipping_method' => 'sometimes|string|max:100',
            'tracking_number' => 'nullable|string|max:50|unique:shipments,tracking_number,'.$shipment->id,
            'estimated_delivery' => 'nullable|date',
            'shipped_at' => 'nullable|date',
            'order_id' => 'sometimes|exists:orders,id',
        ]);

        $shipment->update($request->all());
        return response()->json($shipment);
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return response()->json(['message' => 'Shipment deleted successfully']);
    }
}