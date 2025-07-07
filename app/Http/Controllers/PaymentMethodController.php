<?php

namespace App\Http\Controllers;

use App\Models\payment_method;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storepayment_methodRequest;
use App\Http\Requests\Updatepayment_methodRequest;

class PaymentMethodController extends Controller
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
    public function store(Storepayment_methodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(payment_method $payment_method)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(payment_method $payment_method)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepayment_methodRequest $request, payment_method $payment_method)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payment_method $payment_method)
    {
        //
    }
}
