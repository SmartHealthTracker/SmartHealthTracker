<?php

namespace App\Http\Controllers\Admin; // ✅ doit être Admin

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('resource', 'user')->get();
        return view('admin.comments.index', compact('comments'));
    }

    public function create(Request $request)
    {
        $resources = Resource::all();
        $resource_id = $request->resource_id ?? null;
        return view('admin.comments.create', compact('resources', 'resource_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'content' => 'required|string',
            'date' => 'required|date',
        ]);

        Comment::create([
            'resource_id' => $request->resource_id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'date' => $request->date,
        ]);

        return redirect()->route('comments.index')->with('success', 'Commentaire ajouté avec succès.');
    }

    public function show(Comment $comment)
    {
        $comment->load('resource', 'user');
        return view('admin.comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        $resources = Resource::all();
        return view('admin.comments.edit', compact('comment', 'resources'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'content' => 'required|string',
            'date' => 'required|date',
        ]);

        $comment->update($request->only('resource_id', 'content', 'date'));

        return redirect()->route('comments.index')->with('success', 'Commentaire mis à jour avec succès.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Commentaire supprimé avec succès.');
    }
}
