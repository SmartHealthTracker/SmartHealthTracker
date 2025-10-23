@extends('layout.master')
@section('title', 'Modifier Journal d’Activité')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 offset-md-2">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4">
        <h4 class="mb-0">Modifier le Journal d’Activité</h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('activity_logs.update', $activity_log->id) }}" method="POST">
          @csrf
          @method('PUT')

          {{-- Activité --}}
          <div class="mb-3">
            <label for="activity_id" class="form-label fw-bold">Activité</label>
            <select name="activity_id" id="activity_id" 
                    class="form-select rounded-3 shadow-sm" required>
              @foreach($activities as $activity)
                <option value="{{ $activity->id }}" {{ $activity_log->activity_id == $activity->id ? 'selected' : '' }}>
                  {{ $activity->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Utilisateur --}}
          <div class="mb-3">
            <label for="user_id" class="form-label fw-bold">Utilisateur</label>
            <select name="user_id" id="user_id" 
                    class="form-select rounded-3 shadow-sm" required>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $activity_log->user_id == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Durée --}}
          <div class="mb-3">
            <label for="duration" class="form-label fw-bold">Durée (minutes)</label>
            <input type="number" name="duration" id="duration" 
                   class="form-control rounded-3 shadow-sm"
                   value="{{ $activity_log->duration }}" required min="1" max="1440">
          </div>

          {{-- Date --}}
          <div class="mb-3">
            <label for="date" class="form-label fw-bold">Date</label>
            <input type="date" name="date" id="date" 
                   class="form-control rounded-3 shadow-sm"
                   value="{{ $activity_log->date }}" required>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('activity_logs.index') }}" class="btn btn-secondary fw-bold px-4 rounded-3">
              Annuler
            </a>
            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3">
              Mettre à Jour
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
