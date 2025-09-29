@extends('layout.master')
@section('title', 'Ajouter Activité')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 offset-md-2">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-dark text-white rounded-top-4">
        <h4 class="mb-0">Ajouter une Activité</h4>
      </div>
      <div class="card-body p-4">

        {{-- Formulaire --}}
        <form action="{{ route('activities.store') }}" method="POST" novalidate>
          @csrf

          {{-- Champ Nom --}}
          <div class="form-group mb-3">
            <label for="name" class="fw-bold">Nom</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   required minlength="3" maxlength="100">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Champ Calories --}}
          <div class="form-group mb-3">
            <label for="calories_per_hour" class="fw-bold">Calories par Heure</label>
            <input type="number" name="calories_per_hour" id="calories_per_hour"
                   value="{{ old('calories_per_hour') }}"
                   class="form-control @error('calories_per_hour') is-invalid @enderror"
                   required min="1" max="2000">
            @error('calories_per_hour')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-bold">Enregistrer</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Affichage des erreurs avec SweetAlert --}}
@if ($errors->any())
<script>
  Swal.fire({
    icon: 'error',
    title: 'Erreur de validation',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonText: 'OK'
  });
</script>
@endif

{{-- Message succès --}}
@if (session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false
  });
</script>
@endif
@endsection
