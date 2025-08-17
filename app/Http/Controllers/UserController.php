<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $users = User::query()
            ->included()     
            ->filter()       
            ->sort()         
            ->getOrPaginate(); 

        return response()->json($users);
    }

    
    public function show($id)
    {
        $user = User::query()
            ->included() 
            ->findOrFail($id);

        return response()->json($user);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id', 
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'message' => ' Usuario creado correctamente',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id'  => 'sometimes|exists:roles,id', 
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $user
        ]);
    }

    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => ' Usuario eliminado correctamente'
        ]);
    }
}
