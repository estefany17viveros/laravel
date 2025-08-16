<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        return PaymentType::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $paymentType = PaymentType::create($validated);
        return response()->json($paymentType, 201);
    }

    public function show($id)
    {
        $paymentType = PaymentType::included()->findOrFail($id);
        return response()->json($paymentType);
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $validated = $request->validate([
            'type' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:255',
        ]);

        $paymentType->update($validated);
        return response()->json($paymentType);
    }

    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();
        return response()->json(null, 204);
    }
}
