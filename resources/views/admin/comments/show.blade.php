@extends('layout.master')

@section('content')
<div class="container">
    <h1>Commentaire</h1>
    <p><strong>Ressource :</strong> {{ $comment->resource->title ?? 'N/A' }}</p>
    <p><strong>Utilisateur :</strong> {{ $comment->user->name ?? 'N/A' }}</p>
    <p><strong>Date :</strong> {{ $comment->date }}</p>
    <p>{{ $comment->content }}</p>
    <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-warning">Modifier</a>
    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
    </form>
</div>
@endsection
