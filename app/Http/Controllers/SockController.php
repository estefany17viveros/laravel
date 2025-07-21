<?php

// app/Http/Controllers/SockController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sock;

class SockController extends Controller
{
    public function index()
    {
        return response()->json(Sock::included()->filter()->sort()->getOrPaginate());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'URL' => 'required|string',
            'Upload_Date' => 'required|date',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $sock = Sock::create($request->all());
        return response()->json($sock, 201);
    }

    public function show($id)
    {
        $sock = Sock::findOrFail($id);
        return response()->json($sock);
    }

    public function update(Request $request, Sock $sock)
    {
        $request->validate([
            'type' => 'sometimes|string',
            'URL' => 'sometimes|string',
            'Upload_Date' => 'sometimes|date',
            'topic_id' => 'sometimes|exists:topics,id',
        ]);

        $sock->update($request->all());
        return response()->json($sock);
    }

    public function destroy(Sock $sock)
    {
        $sock->delete();
        return response()->json(['message' => 'Sock deleted']);
    }
}
