<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::included()->filter()->sort()->getOrPaginate();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:0',
            'status' => 'required|string',
            'order_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string',
            'order_date' => 'sometimes|date|nullable',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $order->update($request->all());
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
