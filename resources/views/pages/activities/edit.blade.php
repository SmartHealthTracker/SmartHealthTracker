@extends('layout.master')
@section('title', 'Modifier Activité')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 offset-md-2">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4">
        <h4 class="mb-0">Modifier l’Activité</h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('activities.update', $activity->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nom</label>
            <input type="text" name="name" id="name" 
                   class="form-control rounded-3 shadow-sm" 
                   value="{{ old('name', $activity->name) }}" required>
          </div>

          <div class="mb-3">
            <label for="calories_per_hour" class="form-label fw-bold">Calories par Heure</label>
            <input type="number" name="calories_per_hour" id="calories_per_hour" 
                   class="form-control rounded-3 shadow-sm" 
                   value="{{ old('calories_per_hour', $activity->calories_per_hour) }}" 
                   required min="0">
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('activities.index') }}" class="btn btn-secondary fw-bold px-4 rounded-3">Annuler</a>
            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3">Mettre à Jour</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
