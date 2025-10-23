@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Modifier l'habitude</h2>

    <div class="card shadow-lg p-4 mx-auto" style="max-width: 650px;">
        <form action="{{ route('habitssaif.update', $habit->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Exemple de données réelles --}}
            {{-- $habit->title = "Courir chaque matin" --}}
            {{-- $habit->description = "Faire un jogging de 30 minutes chaque jour" --}}
            {{-- $habit->category = "Santé" --}}
            {{-- $habit->target_value = 30 --}}
            {{-- $habit->unit = "minutes" --}}

            {{-- Champ Titre --}}
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Titre</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="form-control" 
                       value="{{ old('title', $habit->title) }}" 
                       placeholder="Ex : Lire 10 pages par jour" 
                       required>
            </div>

            {{-- Champ Description --}}
            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="3" 
                          class="form-control" 
                          placeholder="Ex : Lire un livre de développement personnel chaque soir">{{ old('description', $habit->description) }}</textarea>
            </div>

            {{-- Champ Catégorie --}}
            <div class="mb-3">
                <label for="category" class="form-label fw-bold">Catégorie</label>
                <select name="category" id="category" class="form-select">
                    <option value="Santé" {{ old('category', $habit->category) == 'Santé' ? 'selected' : '' }}>Santé</option>
                    <option value="Sport" {{ old('category', $habit->category) == 'Sport' ? 'selected' : '' }}>Sport</option>
                    <option value="Productivité" {{ old('category', $habit->category) == 'Productivité' ? 'selected' : '' }}>Productivité</option>
                    <option value="Bien-être" {{ old('category', $habit->category) == 'Bien-être' ? 'selected' : '' }}>Bien-être</option>
                </select>
            </div>

            {{-- Objectif --}}
            <div class="mb-3">
                <label for="target_value" class="form-label fw-bold">Objectif</label>
                <input type="number" 
                       name="target_value" 
                       id="target_value" 
                       class="form-control" 
                       value="{{ old('target_value', $habit->target_value) }}" 
                       placeholder="Ex : 30">
            </div>

            {{-- Unité --}}
            <div class="mb-3">
                <label for="unit" class="form-label fw-bold">Unité</label>
                <input type="text" 
                       name="unit" 
                       id="unit" 
                       class="form-control" 
                       value="{{ old('unit', $habit->unit) }}" 
                       placeholder="Ex : minutes, pages, pas">
            </div>

            {{-- Boutons --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('habitssaif.index') }}" class="btn btn-outline-secondary">⬅ Retour</a>
                <button type="submit" class="btn btn-success">💾 Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
