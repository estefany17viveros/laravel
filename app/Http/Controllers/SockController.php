<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sock;

class SockController extends Controller
{
    public function index()
    {
        $socks = Sock::included()
            ->filter()
            ->sort()
            ->getOrPaginate();
            
        return response()->json($socks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:100',
            'URL' => 'required|url|max:255',
            'Upload_Date' => 'required|date',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'topic_id' => 'required|exists:topics,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $sock = Sock::create($request->except('tags'));
        
        if ($request->has('tags')) {
            $sock->tags()->sync($request->tags);
        }

        return response()->json($sock, 201);
    }

    public function show($id)
    {
        $sock = Sock::included()->findOrFail($id);
        return response()->json($sock);
    }

    public function update(Request $request, Sock $sock)
    {
        $request->validate([
            'type' => 'sometimes|string|max:100',
            'URL' => 'sometimes|url|max:255',
            'Upload_Date' => 'sometimes|date',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'topic_id' => 'sometimes|exists:topics,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $sock->update($request->except('tags'));
        
        if ($request->has('tags')) {
            $sock->tags()->sync($request->tags);
        }

        return response()->json($sock);
    }

    public function destroy(Sock $sock)
    {
        $sock->tags()->detach();
        $sock->delete();
        return response()->json(['message' => 'Sock deleted successfully']);
    }
}