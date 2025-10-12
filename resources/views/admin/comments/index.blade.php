@extends('layout.master')

@section('content')
<div class="container">
    <h1>Commentaires</h1>
    <a href="{{ route('comments.create') }}" class="btn btn-primary mb-3">Ajouter un Commentaire</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ressource</th>
                <th>Utilisateur</th>
                <th>Contenu</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->resource->title ?? 'N/A' }}</td>
                <td>{{ $comment->user->name ?? 'N/A' }}</td>
                <td>{{ $comment->content }}</td>
                <td>{{ $comment->date }}</td>
                <td>
                    <a href="{{ route('comments.show', $comment->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
