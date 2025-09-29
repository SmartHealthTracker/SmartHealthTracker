<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // ✅ Ajouter cette ligne
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with('user')->get();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        Resource::create([
            'title' => $request->title,
            'content' => $request->input('content'),
            'category' => $request->category,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('resources.index')->with('success', 'Ressource créée avec succès.');
    }

    public function show(Resource $resource)
    {
        // Charger les commentaires et l'utilisateur
        $resource->load('comments.user', 'user');
        return view('admin.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        $resource->update($request->only('title', 'content', 'category'));

        return redirect()->route('resources.index')->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Ressource supprimée avec succès.');
    }
}
