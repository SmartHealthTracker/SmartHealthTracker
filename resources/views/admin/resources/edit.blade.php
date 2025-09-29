@extends('layout.master')

@section('content')
<div class="container">
    <h1>Modifier la ressource</h1>

    <form action="{{ route('resources.update', $resource->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $resource->title }}" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <input type="text" name="category" id="category" class="form-control" value="{{ $resource->category }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" id="content" class="form-control" required>{{ $resource->content }}</textarea>
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
        <a href="{{ route('resources.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
