@extends('layout.master')
@section('title', 'Activités')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4 d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Gestion des Activités</h4>
        <a href="{{ route('activities.create') }}" class="btn btn-primary fw-bold rounded-3">+ Ajouter Activité</a>
      </div>
      <div class="card-body">

        <div class="table-responsive mt-3">
          <table class="table table-hover table-bordered align-middle rounded-3">
            <thead class="table-light text-center">
              <tr>
                <th>Nom</th>
                <th>Calories par Heure</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($activities as $activity)
                <tr class="text-center align-middle">
                  <td class="fw-bold">{{ $activity->name }}</td>
                  <td>{{ $activity->calories_per_hour }}</td>
                  <td class="d-flex justify-content-center gap-2">
                    <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-sm btn-warning rounded-3">Modifier</a>

                    <form class="delete-form" action="{{ route('activities.destroy', $activity->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger rounded-3">Supprimer</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center">Aucune activité trouvée.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Êtes-vous sûr ?',
      text: "Cette action est irréversible.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#198754',
      cancelButtonColor: '#d33',
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