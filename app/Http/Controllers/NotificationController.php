<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $query = Notification::query();
        
        // Filtro adicional para notificaciones no leídas
        if (request('unread')) {
            $query->unread();
        }
        
        // Filtro por tipo de notificación
        if (request('type')) {
            $query->where('type', request('type'));
        }
        
        // Filtro por usuario específico
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        
        return $query->included()->filter()->sort()->getOrPaginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'type' => 'nullable|string|max:50',
            'is_read' => 'nullable|boolean'
        ]);

        $notification = Notification::create($validated);
        return response()->json($notification, 201);
    }

    public function show($id)
    {
        $notification = Notification::included()->findOrFail($id);
        return response()->json($notification);
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'type' => 'nullable|string|max:50',
            'is_read' => 'nullable|boolean'
        ]);

        $notification->update($validated);
        return response()->json($notification);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json($notification);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(null, 204);
    }
}