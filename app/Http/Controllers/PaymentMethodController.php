<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $query = PaymentMethod::query();
        
        // Filtro por usuario
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        
        // Mostrar solo métodos activos (no expirados)
        if (request('active')) {
            $query->active();
        }
        
        // Mostrar solo métodos por defecto
        if (request('default')) {
            $query->default();
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'types' => 'required|string|max:100|in:credit_card,debit_card,paypal,bank_transfer',
            'details' => 'required|string|max:255',
            'expiration_date' => 'required|date|after_or_equal:today',
            'CCV' => 'required|integer|digits_between:3,4',
            'is_default' => 'nullable|boolean',
            'user_id' => 'required|exists:users,id'
        ]);

        // Si se marca como default, quitar default de otros métodos del usuario
        if ($validated['is_default'] ?? false) {
            PaymentMethod::where('user_id', $validated['user_id'])
                ->update(['is_default' => false]);
        }

        $method = PaymentMethod::create($validated);
        return response()->json($method, 201);
    }

    public function show($id)
    {
        $method = PaymentMethod::included()->findOrFail($id);
        return response()->json($method);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'types' => 'sometimes|string|max:100|in:credit_card,debit_card,paypal,bank_transfer',
            'details' => 'sometimes|string|max:255',
            'expiration_date' => 'sometimes|date|after_or_equal:today',
            'CCV' => 'sometimes|integer|digits_between:3,4',
            'is_default' => 'nullable|boolean',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        // Si se marca como default, quitar default de otros métodos del usuario
        if ($validated['is_default'] ?? false) {
            PaymentMethod::where('user_id', $paymentMethod->user_id)
                ->where('id', '!=', $paymentMethod->id)
                ->update(['is_default' => false]);
        }

        $paymentMethod->update($validated);
        return response()->json($paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json(null, 204);
    }

    // Método adicional para marcar como predeterminado
    public function markAsDefault(PaymentMethod $paymentMethod)
    {
        PaymentMethod::where('user_id', $paymentMethod->user_id)
            ->update(['is_default' => false]);
            
        $paymentMethod->update(['is_default' => true]);
        return response()->json($paymentMethod);
    }
}