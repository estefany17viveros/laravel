<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
{
    $query = OrderItem::query();

    //  Si viene un parámetro "order_status", aplicar el filtro
    if (request('order_status')) {
        $query->whereOrderStatus(request('order_status'));
    }
   
    //  Luego encadenar los otros scopes
    return $query->included()->filter()->sort()->getOrPaginate();
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'order_id' => 'nullable|exists:orders,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        return OrderItem::create($validated);
    }

    public function show(OrderItem $orderItem)
    {
        return $orderItem->load(['order', 'product']);
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'order_id' => 'nullable|exists:orders,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $orderItem->update($validated);
        return $orderItem;
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->noContent();
    }
}
                                                                                                                      