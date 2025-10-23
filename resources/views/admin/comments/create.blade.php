@extends('layout.master')

@section('content')
<div class="container">
    <h1>Ajouter un Commentaire</h1>
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="resource_id" class="form-label">Ressource</label>
            <select name="resource_id" class="form-control" required>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" @if(isset($resource_id) && $resource->id == $resource_id) selected @endif>{{ $resource->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
<<<<<<< HEAD
            <textarea name="content" class="form-control" rows="3" required></textarea>
=======
            <textarea name="content" class="form-control" rows="3" required minlength="20" maxlength="500"></textarea>
>>>>>>> Ramez
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
