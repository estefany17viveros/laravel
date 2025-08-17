<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
  public function index()
{
    $roles = Role::included()
        ->filter()
        ->sort()
        ->GetOrPaginate();

    return response()->json($roles);
}

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:admin,customer,veterinarian,trainer',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
         $validated = $request->validate([
        'type' => 'sometimes|string|in:admin,customer,veterinarian,trainer', ]);

    $role->update($validated);

    return response()->json($role);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
