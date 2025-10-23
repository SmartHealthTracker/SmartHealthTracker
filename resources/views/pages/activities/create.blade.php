@extends('layout.master')
@section('title', 'Ajouter Activité')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 offset-md-2">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4">
        <h4 class="mb-0">Ajouter une Activité</h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('activities.store') }}" method="POST" novalidate>
          @csrf

          {{-- Champ Nom --}}
          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nom</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name') }}"
                   class="form-control rounded-3 shadow-sm @error('name') is-invalid @enderror"
                   required minlength="3" maxlength="100">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Champ Calories --}}
          <div class="mb-3">
            <label for="calories_per_hour" class="form-label fw-bold">Calories par Heure</label>
            <input type="number" name="calories_per_hour" id="calories_per_hour"
                   value="{{ old('calories_per_hour') }}"
                   class="form-control rounded-3 shadow-sm @error('calories_per_hour') is-invalid @enderror"
                   required min="1" max="2000">
            @error('calories_per_hour')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('activities.index') }}" class="btn btn-secondary fw-bold px-4 rounded-3">Annuler</a>
            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
