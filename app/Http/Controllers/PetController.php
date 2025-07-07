<?php

namespace App\Http\Controllers;

use App\Models\pet;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorepetRequest;
use App\Http\Requests\UpdatepetRequest;

class PetController extends Controller
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
    public function store(StorepetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(pet $pet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pet $pet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepetRequest $request, pet $pet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pet $pet)
    {
        //
    }
}
