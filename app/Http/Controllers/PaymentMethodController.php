<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethod::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'            => 'required|string|max:100|in:credit_card,debit_card,paypal,bank_transfer',
            'description'     => 'required|string|max:255',
            'expiration_date' => 'required|date|after_or_equal:today',
            'payment_id'      => 'nullable|exists:payments,id',
        ]);

        $method = PaymentMethod::create($validated);
        return response()->json($method, 201);
    }

    public function show($id)
    {
        $method = PaymentMethod::include(request('include'))->findOrFail($id);
        return response()->json($method);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'type'            => 'sometimes|string|max:100|in:credit_card,debit_card,paypal,bank_transfer',
            'description'     => 'sometimes|string|max:255',
            'expiration_date' => 'sometimes|date|after_or_equal:today',
            'payment_id'      => 'nullable|exists:payments,id',
        ]);

        $paymentMethod->update($validated);
        return response()->json($paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json(null, 204);
    }
}
