<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function index()
    {
        $items = OrderItem::included()->filter()->sort()->getOrPaginate();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $item = OrderItem::create($request->all());
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = OrderItem::with(['order', 'product'])->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'order_id' => 'sometimes|required|exists:orders,id',
            'product_id' => 'sometimes|required|exists:products,id',
        ]);

        $orderItem->update($request->all());
        return response()->json($orderItem);
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->json(['message' => 'OrderItem deleted']);
    }
}
