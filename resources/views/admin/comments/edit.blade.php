@extends('layout.master')

@section('content')
<div class="container">
    <h1>Modifier le Commentaire</h1>
    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Ressource --}}
        <div class="mb-3">
            <label for="resource_id" class="form-label">Ressource</label>
            <select name="resource_id" class="form-control" required>
                @foreach($resources as $resource)
                    <option value="{{ $resource->id }}" @if($comment->resource_id == $resource->id) selected @endif>
                        {{ $resource->title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Contenu --}}
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea id="content" name="content" class="form-control" rows="3" required
                      minlength="20" maxlength="500" 
                      pattern=".{20,500}" 
                      title="Le commentaire doit contenir entre 20 et 500 caractères">{{ $comment->content }}</textarea>
            <small id="charCount" class="form-text text-muted">0 / 500 caractères</small>
        </div>

        {{-- Date --}}
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $comment->date }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

{{-- Compteur de caractères JS --}}
<script>
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');

    // Initialiser le compteur au chargement
    charCount.textContent = `${textarea.value.length} / 500 caractères`;

    textarea.addEventListener('input', () => {
        const length = textarea.value.length;
        charCount.textContent = `${length} / 500 caractères`;
    });
</script>
@endsection
