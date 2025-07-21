<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingCart;

class ShoppingCartController extends Controller
{
    public function index()
    {
        $carts = ShoppingCart::included()->filter()->sort()->getOrPaginate();
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'creation_date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $cart = ShoppingCart::create($request->all());
        return response()->json($cart, 201);
    }

    public function show($id)
    {
        $cart = ShoppingCart::with(['user', 'product', 'order'])->findOrFail($id);
        return response()->json($cart);
    }

    public function update(Request $request, ShoppingCart $shoppingCart)
    {
        $request->validate([
            'creation_date' => 'sometimes|date',
            'quantity' => 'sometimes|numeric|min:0',
            'user_id' => 'sometimes|exists:users,id',
            'product_id' => 'sometimes|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $shoppingCart->update($request->all());
        return response()->json($shoppingCart);
    }

    public function destroy(ShoppingCart $shoppingCart)
    {
        $shoppingCart->delete();
        return response()->json(['message' => 'Shopping cart item deleted']);
    }
}
