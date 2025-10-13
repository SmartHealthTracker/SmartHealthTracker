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

    {{-- 🔹 Recommandations --}}
    @if($recommended->isNotEmpty())
        <hr>
        <h3>Ressources recommandées :</h3>
        <div class="row">
            @foreach($recommended as $rec)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/images/' . ($rec->image ?? 'default-resource.jpg')) }}" 
                             class="card-img-top" 
                             style="height:200px; object-fit:cover;" 
                             alt="{{ $rec->title }}">
                        <div class="card-body">
                            <h6>{{ $rec->title }}</h6>
                            <p>{{ Str::limit($rec->content, 80) }}</p>
                            <a href="{{ route('resources.show', $rec->id) }}" class="btn btn-sm btn-primary">Voir</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
