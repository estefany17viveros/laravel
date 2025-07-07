<?php

namespace App\Http\Controllers;

use App\Models\shopping_cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storeshopping_cartRequest;
use App\Http\Requests\Updateshopping_cartRequest;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeshopping_cartRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(shopping_cart $shopping_cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shopping_cart $shopping_cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateshopping_cartRequest $request, shopping_cart $shopping_cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shopping_cart $shopping_cart)
    {
        //
    }
}
