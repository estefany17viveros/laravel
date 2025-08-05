<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();
        
        // Filtro por rango de fechas
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }
        
        // Filtro por rango de montos
        if ($request->has('min_amount') || $request->has('max_amount')) {
            if ($request->has('min_amount')) {
                $query->where('amount', '>=', $request->input('min_amount'));
            }
            if ($request->has('max_amount')) {
                $query->where('amount', '<=', $request->input('max_amount'));
            }
        }
        
        if ($request->has('status')) {
            $query->status($request->input('status'));
        }
        
        // Filtro por método de pago
        if ($request->has('payment_method_id')) {
            $query->where('payment_method_id', $request->input('payment_method_id'));
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function show(Payment $payment)
    {
        $payment->load(
            explode(',', request('included', 'payable,paymentMethod'))
        );
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled,refunded',
            'payable_id' => 'required|integer',
            'payable_type' => 'required|string|in:orders,invoices', // Tipos permitidos
            'payment_method_id' => 'required|exists:payment_methods,id',
            'transaction_id' => 'nullable|string|max:100',
            'user_id' => 'required|exists:users,id'
        ]);

        $payment = Payment::create($validated);
        return response()->json($payment, 201);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled,refunded',
            'payable_id' => 'sometimes|integer',
            'payable_type' => 'sometimes|string|in:orders,invoices',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'transaction_id' => 'nullable|string|max:100',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204);
    }

    // Método adicional para cambiar estado
    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled,refunded'
        ]);

        $payment->update(['status' => $validated['status']]);
        return response()->json($payment);
    }
}