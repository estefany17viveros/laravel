<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::included()->filter()->sort()->getOrPaginate());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
            'veterinarian_id'=>'required|exists:veterinarians,id',
            'category_id' => 'required|exists:categories,id',
            'shopping_carts_id' => 'required|exists:shopping_carts,id',


        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'image' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'veterinarian_id' => 'sometimes|exists:veterinarians,id',
            'shopping_carts_id' => 'sometimes|exists:shopping_carts,id',

        ]);

        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
