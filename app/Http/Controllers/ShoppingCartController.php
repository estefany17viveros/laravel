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
            'price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        // Calcular el total si no se proporciona
        if (!$request->has('total')) {
            $request->merge(['total' => $request->price * $request->quantity]);
        }

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
            'total' => 'sometimes|numeric|min:0',
            'user_id' => 'sometimes|exists:users,id',
            'product_id' => 'sometimes|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        // Recalcular el total si cambia quantity o price
        if ($request->has('quantity') || $request->has('price')) {
            $quantity = $request->has('quantity') ? $request->quantity : $shoppingCart->quantity;
            $price = $request->has('price') ? $request->price : $shoppingCart->price;
            $request->merge(['total' => $quantity * $price]);
        }

        $shoppingCart->update($request->all());
        return response()->json($shoppingCart);
    }

    public function destroy(ShoppingCart $shoppingCart)
    {
        $shoppingCart->delete();
        return response()->json(['message' => 'Shopping cart item deleted successfully']);
    }
}