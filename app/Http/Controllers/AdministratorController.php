<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function index()
    {
        $administrators = Administrator::included()->filter()->sort()->getOrPaginate();
        return response()->json($administrators);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:administrators,email',
            'password'    => 'required|string|min:6',
            'status'      => 'boolean',
            'phone_number'=> 'nullable|string',
            'user_id'     => 'required|exists:users,id',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $administrator = Administrator::create($data);
        return response()->json($administrator, 201);
    }

    public function show($id)
    {
        $administrator = Administrator::with('user')->findOrFail($id);
        return response()->json($administrator);
    }

    public function update(Request $request, Administrator $administrator)
    {
        $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:administrators,email,' . $administrator->id,
            'password'     => 'sometimes|string|min:6',
            'status'       => 'boolean',
            'phone_number' => 'nullable|string',
            'user_id'      => 'sometimes|exists:users,id',
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $administrator->update($data);
        return response()->json($administrator);
    }

    public function destroy(Administrator $administrator)
    {
        $administrator->delete();
        return response()->json(['message' => 'Administrator deleted successfully']);
    }
}
