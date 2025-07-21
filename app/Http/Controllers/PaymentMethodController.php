<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::included()->filter()->sort()->getOrPaginate();
        return response()->json($methods);
    }

    public function store(Request $request)
    {
        $request->validate([
            'types' => 'required|string|max:100',
            'details' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'CCV' => 'required|integer|min:100|max:999',
        ]);

        $method = PaymentMethod::create($request->all());
        return response()->json($method, 201);
    }

    public function show($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return response()->json($method);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'types' => 'sometimes|string|max:100',
            'details' => 'sometimes|string|max:255',
            'expiration_date' => 'sometimes|date',
            'CCV' => 'sometimes|integer|min:100|max:999',
        ]);

        $paymentMethod->update($request->all());
        return response()->json($paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
