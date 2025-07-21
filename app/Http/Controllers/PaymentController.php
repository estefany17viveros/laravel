<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::included()->filter()->sort()->getOrPaginate();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['order', 'paymentMethod'])->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'sometimes|integer|min:0',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'order_id' => 'sometimes|exists:orders,id',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
        ]);

        $payment->update($request->all());
        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
