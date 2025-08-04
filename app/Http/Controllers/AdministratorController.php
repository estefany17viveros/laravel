<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function index()
    {
        $administrators = Administrator::included()  // Aplica el scope 'included', que carga relaciones si se pasan por la URL (por ejemplo, ?included=user).
            ->filter()   // Aplica filtros si vienen en la URL
            ->sort()    // Ordena los resultados si se indica en la URL (por ejemplo, ?sort=-email para ordenar por email descendente)
            ->withUserEmail()              //  Consulta anidada: carga la relación con User para tener el email del usuario que pertenece al administrador
            ->withAppointmentCount()      //  Consulta anidada: cuenta las citas que tiene el usuario relacionado y las agrega como total_appointment
            ->getOrPaginate()  // Si viene Page en la URL, pagina los resultados. Si no, los trae todos.
            ->map(function ($admin) { // Mapea cada administrador para devolver solo los campos necesarios en el JSON final
                return [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'status' => $admin->status,
                    'phone_number' => $admin->phone_number,
                    'user_email' => optional($admin->user)->email, // Muestra el email del usuario si existe (relación belongsTo con User)
                    'total_appointments' => $admin->total_appointments ?? 0, // Muestra la cantidad de citas que tiene el usuario. Si no hay, devuelve 0.
            
                ];
            });

        return response()->json($administrators); // Devuelve el JSON con los administradores procesados.
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

