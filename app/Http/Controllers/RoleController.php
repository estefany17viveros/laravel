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
        ->orPaginate();

    return response()->json($roles);
}

    public function store(Request $request)
    {
        $request->validate([
            'name_role' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'roleable_id' => 'required|integer',
            'roleable_type' => 'required|string'
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
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
