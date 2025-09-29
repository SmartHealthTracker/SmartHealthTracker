@extends('layout.master')

@section('content')
<div class="container">
    <h1>Liste des ressources</h1>
    <a href="{{ route('resources.create') }}" class="btn btn-primary mb-3">Ajouter une ressource</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Créé par</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resources as $resource)
            <tr>
                <td>{{ $resource->id }}</td>
                <td>{{ $resource->title }}</td>
                <td>{{ $resource->category }}</td>
                <td>{{ $resource->user->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('resources.show', $resource->id) }}" class="btn btn-info btn-sm">Voir</a>
                    <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" style="display:inline-block;">
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
