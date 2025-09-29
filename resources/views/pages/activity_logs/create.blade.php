@extends('layout.master')
@section('title', 'Ajouter Journal d’Activité')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 offset-md-2">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-dark text-white rounded-top-4">
        <h4 class="mb-0">Ajouter un Journal d’Activité</h4>
      </div>
      <div class="card-body p-4">

        <form action="{{ route('activity_logs.store') }}" method="POST" novalidate>
          @csrf

          {{-- Activité --}}
          <div class="form-group mb-3">
            <label for="activity_id" class="fw-bold">Activité</label>
            <select name="activity_id" id="activity_id" class="form-control @error('activity_id') is-invalid @enderror" required>
              <option value="">-- Sélectionnez une activité --</option>
              @foreach($activities as $activity)
                <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                  {{ $activity->name }}
                </option>
              @endforeach
            </select>
            @error('activity_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Utilisateur --}}
          <div class="form-group mb-3">
            <label for="user_id" class="fw-bold">Utilisateur</label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
              <option value="">-- Sélectionnez un utilisateur --</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
              @endforeach
            </select>
            @error('user_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Durée --}}
          <div class="form-group mb-3">
            <label for="duration" class="fw-bold">Durée (minutes)</label>
            <input type="number" name="duration" id="duration" 
                   class="form-control @error('duration') is-invalid @enderror"
                   value="{{ old('duration') }}" required min="1" max="1440">
            @error('duration')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Date --}}
          <div class="form-group mb-3">
            <label for="date" class="fw-bold">Date</label>
            <input type="date" name="date" id="date" 
                   class="form-control @error('date') is-invalid @enderror"
                   value="{{ old('date') }}" required>
            @error('date')
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
