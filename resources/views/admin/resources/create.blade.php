@extends('layout.master')

@section('content')
<div class="container">
    <h1>Ajouter une ressource</h1>

    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Catégorie</label>
            <input type="text" name="category" id="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" id="content" class="form-control" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="{{ route('resources.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
