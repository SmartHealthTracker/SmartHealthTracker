@extends('layout.master')
@section('title', 'Journaux d’Activités')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Liste des Journaux d’Activités</h4>
        <a href="{{ route('activity_logs.create') }}" class="btn btn-primary fw-bold rounded-3">
          + Ajouter Journal
        </a>
      </div>
      <div class="card-body">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Activité</th>
              <th>Utilisateur</th>
              <th>Durée (min)</th>
              <th>Calories Brûlées</th>
              <th>Date</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $log)
            <tr>
              <td>{{ $log->activity->name }}</td>
              <td>{{ $log->user->name }}</td>
              <td>{{ $log->duration }}</td>
              <td>{{ $log->calories_burned }}</td>
              <td>{{ $log->date }}</td>
              <td class="text-center">
                <a href="{{ route('activity_logs.edit', $log->id) }}" 
                   class="btn btn-sm btn-warning rounded-3 fw-bold">Modifier</a>

                <form action="{{ route('activity_logs.destroy', $log->id) }}" 
                      method="POST" class="d-inline delete-form">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger rounded-3 fw-bold">
                    Supprimer
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Confirmation de suppression
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>

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
