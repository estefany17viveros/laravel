<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->included()      
            ->filter()        
            ->sort()          
            ->getOrPaginate();

        return response()->json($payments);
    }

    /**
     *  Mostrar un pago específico con sus relaciones.
     */
    public function show(Payment $payment)
    {
        $payment->load(request('included', 'payable,paymentMethod'));

        return response()->json($payment);
    }

    /**
     *  Crear un nuevo pago polimórfico.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|integer',
            'date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payable_id' => 'required|integer',      
            'payable_type' => 'required|string',     
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        //  Crea el pago polimórfico
        $payment = Payment::create($data);

        return response()->json($payment, 201);
    }

    /**
     *  Actualizar un pago.
     */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'amount' => 'sometimes|integer',
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'payable_id' => 'sometimes|integer',
            'payable_type' => 'sometimes|string',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
        ]);

        $payment->update($data);

        return response()->json($payment);
    }

   
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
