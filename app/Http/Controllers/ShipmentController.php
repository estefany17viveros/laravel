<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::included()->filter()->sort()->getOrPaginate();
        return response()->json($shipments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|string',
            'shipping_method' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        $shipment = Shipment::create($request->all());
        return response()->json($shipment, 201);
    }

    public function show($id)
    {
        $shipment = Shipment::with('order')->findOrFail($id);
        return response()->json($shipment);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipping_address' => 'sometimes|string',
            'cost' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string',
            'shipping_method' => 'sometimes|string',
            'order_id' => 'sometimes|exists:orders,id',
        ]);

        $shipment->update($request->all());
        return response()->json($shipment);
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return response()->json(['message' => 'Shipment deleted']);
    }
}
