<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingCart;

class ShoppingCartController extends Controller
{
    public function index()
    {
        $carts = ShoppingCart::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($carts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'creation_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $cart = ShoppingCart::create($request->all());
        return response()->json($cart, 201);
    }

    public function show($id)
    {
        $cart = ShoppingCart::included()->findOrFail($id);
        return response()->json($cart);
    }

    public function update(Request $request, ShoppingCart $shoppingCart)
    {
        $request->validate([
            'creation_date' => 'sometimes|date',
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $shoppingCart->update($request->all());
        return response()->json($shoppingCart);
    }

    public function destroy(ShoppingCart $shoppingCart)
    {
        $shoppingCart->delete();
        return response()->json(['message' => 'Shopping cart item deleted successfully']);
    }
}