@extends('layout.master')

@section('content')
<div class="container">
    <h1>Modifier le Commentaire</h1>
    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="resource_id" class="form-label">Ressource</label>
            <select name="resource_id" class="form-control" required>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" @if($comment->resource_id == $resource->id) selected @endif>{{ $resource->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" class="form-control" rows="3" required>{{ $comment->content }}</textarea>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $comment->date }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
