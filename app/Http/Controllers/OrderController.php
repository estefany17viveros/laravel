<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::query();
        
        // Filtro por rango de fechas
        if (request('start_date') && request('end_date')) {
            $query->whereBetween('order_date', [
                request('start_date'),
                request('end_date')
            ]);
        }
        
        // Filtro por rango de total
        if (request('min_total') || request('max_total')) {
            if (request('min_total')) {
                $query->where('total', '>=', request('min_total'));
            }
            if (request('max_total')) {
                $query->where('total', '<=', request('max_total'));
            }
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'order_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::create($validated);
        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::included()->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,processing,completed,cancelled',
            'order_date' => 'sometimes|date|nullable',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
}