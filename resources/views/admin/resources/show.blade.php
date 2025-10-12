@extends('layout.master')

@section('content')
<div class="container">
    <h1>Détails de la ressource</h1>

    <p><strong>Titre :</strong> {{ $resource->title }}</p>
    <p><strong>Catégorie :</strong> {{ $resource->category }}</p>
    <p><strong>Créé par :</strong> {{ $resource->user->name ?? 'N/A' }}</p>
    <p><strong>Contenu :</strong></p>
    <p>{{ $resource->content }}</p>

    <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-warning">Modifier</a>
    <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
    </form>
    <a href="{{ route('resources.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
