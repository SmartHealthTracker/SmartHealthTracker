@extends('layout.master')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-light text-dark rounded-top-4">
        <h4 class="card-title mb-0">Gestion des Utilisateurs</h4>
      </div>
      <div class="card-body">
        {{-- Message succès --}}
        @if(session('success'))
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

        {{-- Tableau utilisateurs --}}
        <div class="table-responsive mt-3">
          <table class="table table-hover table-bordered align-middle rounded-3" 
                 style="border-collapse: separate; border-spacing: 0 10px;">
            <thead class="table-light text-center rounded-3">
              <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Date de Création</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr class="text-center align-middle shadow-sm" 
                    style="background-color: #f8f9fa; border-radius: 10px;">
                  <td class="fw-bold">{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                  <td>
                    @if($user->is_blocked)
                      <span class="badge bg-danger">Bloqué</span>
                    @else
                      <span class="badge bg-success">Actif</span>
                    @endif
                  </td>
                  <td class="d-flex justify-content-center gap-2">
                    {{-- Blocage / Déblocage --}}
                    <form class="block-form" action="{{ route('users.toggleBlock', $user->id) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <button type="button" 
                              class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-warning text-dark' }}"
                              onclick="confirmAction(this.form, '{{ $user->is_blocked ? 'Débloquer' : 'Bloquer' }}')">
                        {{ $user->is_blocked ? 'Débloquer' : 'Bloquer' }}
                      </button>
                    </form>

                    {{-- Suppression --}}
                    <form class="delete-form" action="{{ route('users.destroy', $user->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-sm btn-danger"
                        onclick="confirmAction(this.form, 'Supprimer cet utilisateur')">
                        Supprimer
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Bouton suppression totale --}}
        <div class="mt-4 d-flex justify-content-center">
          <form id="delete-all-form" action="{{ route('users.deleteAll') }}" method="POST" class="w-50">
            @csrf
            <button type="button" class="btn btn-danger w-100 fw-bold"
              onclick="confirmAction(this.form, 'Supprimer tous les utilisateurs')">
              Supprimer Tous les Utilisateurs
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmAction(form, action) {
  Swal.fire({
    title: `Êtes-vous sûr de vouloir ${action} ?`,
    text: "Cette action est irréversible.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#198754', 
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui',
    cancelButtonText: 'Annuler'
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
}
</script>
@endsection
