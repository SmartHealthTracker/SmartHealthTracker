@extends('layout.master')

@section('title', 'Participations')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="row mt-4">
    <div class="col-md-12">

        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">📝 Participations</h4>
            <button class="btn btn-success btn-sm shadow-sm" id="addParticipationBtn">
                <i class="mdi mdi-plus"></i> Ajouter une participation
            </button>
        </div>

        {{-- Section filtres --}}
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="mdi mdi-filter-outline"></i> Filtres</h6>
                <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" id="toggleFilterBtn">
                    <i class="mdi mdi-chevron-down"></i> Afficher les filtres
                </button>
            </div>
            <div class="card-body border-top bg-light p-4 filter-panel" id="filterPanel" style="display: none;">
                <div class="row g-3 align-items-center justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="mdi mdi-magnify text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Rechercher par utilisateur ou challenge">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="ageFilter" class="form-select shadow-sm">
                            <option value="">Filtrer par âge</option>
                            <option value="0-25">0 - 25</option>
                            <option value="26-50">26 - 50</option>
                            <option value="51-65">51 - 65</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-center">
                        <button id="clearFiltersBtn" class="btn btn-outline-secondary w-100">
                            <i class="mdi mdi-refresh"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover mb-0" id="participationTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Challenge</th>
                            <th>Utilisateur</th>
                            <th>Âge</th>
                            <th>Poids</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participations as $index => $part)
                        <tr data-id="{{ $part->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <select class="form-select form-select-sm challenge-select" required>
                                    @foreach($challenges as $challenge)
                                        <option value="{{ $challenge->id }}" @if($challenge->id == $part->challenge_id) selected @endif>
                                            {{ $challenge->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="user-name-span">{{ $part->user->name }}</span></td>
                            <td><input type="number" class="form-control form-control-sm age-input" value="{{ $part->age }}" min="0" max="120"></td>
                            <td><input type="number" step="0.1" class="form-control form-control-sm weight-input" value="{{ $part->weight }}" min="0"></td>
                            <td>
                                <span class="badge 
                                    @if($part->status=='approved') bg-success 
                                    @elseif($part->status=='pending') bg-warning 
                                    @else bg-secondary @endif">
                                    {{ ucfirst($part->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success saveBtn me-1"><i class="mdi mdi-content-save"></i></button>
                                <button class="btn btn-sm btn-danger deleteBtn"><i class="mdi mdi-delete"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3 gap-3 pb-3">
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Précédent</button>
                    <span id="pageInfo" class="fw-bold"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajouter une participation --}}
<div class="modal fade" id="addParticipationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addParticipationForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Ajouter une participation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Challenge</label>
                <select name="challenge_id" class="form-select" required>
                    @foreach($challenges as $challenge)
                        <option value="{{ $challenge->id }}">{{ $challenge->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Nom de l'utilisateur</label>
                <input type="text" name="user_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Âge</label>
                <input type="number" name="age" class="form-control" min="0" max="120" required>
            </div>
            <div class="mb-3">
                <label>Poids</label>
                <input type="number" name="weight" step="0.1" class="form-control" min="0" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Ajouter</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('#participationTable tbody');
    let rows = Array.from(table.rows);
    const searchInput = document.getElementById('searchInput');
    const ageFilter = document.getElementById('ageFilter');
    const rowsPerPage = 5;
    let currentPage = 1;

    const toggleBtn = document.getElementById('toggleFilterBtn');
    const panel = document.getElementById('filterPanel');
    toggleBtn.addEventListener('click', () => {
        const isVisible = panel.style.display === 'block';
        panel.style.display = isVisible ? 'none' : 'block';
        toggleBtn.innerHTML = isVisible 
            ? '<i class="mdi mdi-chevron-down"></i> Afficher les filtres'
            : '<i class="mdi mdi-chevron-up"></i> Masquer les filtres';
    });

    document.getElementById('clearFiltersBtn').addEventListener('click', () => {
        searchInput.value = '';
        ageFilter.value = '';
        currentPage = 1;
        renderTable();
    });

    function validateRow(userName, age, weight, challengeId){
        const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
        if(!userName.trim()) { alert('Le nom de l’utilisateur ne peut pas être vide'); return false; }
        if(!nameRegex.test(userName)) { alert('Le nom ne peut contenir que des lettres et espaces'); return false; }
        if(age === '' || isNaN(age) || age < 0 || age > 120) { alert('Âge invalide'); return false; }
        if(weight === '' || isNaN(weight) || weight < 0) { alert('Poids invalide'); return false; }
        if(!challengeId) { alert('Veuillez sélectionner un challenge'); return false; }
        return true;
    }

    function renderTable() {
        const query = searchInput.value.toLowerCase();
        const ageRange = ageFilter.value;
        const filtered = rows.filter(row => {
            const challenge = row.querySelector('.challenge-select option:checked').textContent.toLowerCase();
            const user = row.querySelector('.user-name-span').textContent.toLowerCase();
            const age = parseInt(row.querySelector('.age-input').value || 0);

            let match = user.includes(query) || challenge.includes(query);
            if(ageRange){
                const [min,max] = ageRange.split('-').map(Number);
                match = match && age >= min && age <= max;
            }
            return match;
        });

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if(currentPage > totalPages) currentPage = totalPages || 1;

        const start = (currentPage-1)*rowsPerPage;
        const paginated = filtered.slice(start, start+rowsPerPage);

        table.innerHTML = '';
        paginated.forEach((row, idx)=>{
            row.cells[0].textContent = start+idx+1;
            table.appendChild(row);
        });

        document.getElementById('pageInfo').textContent = `Page ${currentPage} sur ${totalPages || 1}`;
    }

    searchInput.addEventListener('input', ()=>{ currentPage=1; renderTable(); });
    ageFilter.addEventListener('change', ()=>{ currentPage=1; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', ()=>{ if(currentPage>1){ currentPage--; renderTable(); } });
    document.getElementById('nextPage').addEventListener('click', ()=>{ currentPage++; renderTable(); });

    renderTable();

    const addModal = new bootstrap.Modal(document.getElementById('addParticipationModal'));
    document.getElementById('addParticipationBtn').addEventListener('click', ()=>addModal.show());

    document.getElementById('addParticipationForm').addEventListener('submit', function(e){
        e.preventDefault();
        const form = this;
        const userName = form.user_name.value.trim();
        const age = parseInt(form.age.value);
        const weight = parseFloat(form.weight.value);
        const challengeId = form.challenge_id.value;

        if(!validateRow(userName, age, weight, challengeId)) return;

        const formData = new FormData(form);
        fetch("{{ route('participations.store') }}", {
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: formData
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.error || data.errors){
                alert(data.error || JSON.stringify(data.errors));
            } else {
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', data.id);
                newRow.innerHTML = `
                    <td></td>
                    <td>
                        <select class="form-select form-select-sm challenge-select">
                            @foreach($challenges as $challenge)
                                <option value="{{ $challenge->id }}" ${data.challenge_name == "{{ $challenge->name }}" ? "selected" : ""}>{{ $challenge->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><span class="user-name-span">${data.user_name}</span></td>
                    <td><input type="number" class="form-control form-control-sm age-input" value="${age}" min="0" max="120"></td>
                    <td><input type="number" step="0.1" class="form-control form-control-sm weight-input" value="${weight}" min="0"></td>
                    <td><span class="badge ${data.status=='approved'?'bg-success':data.status=='pending'?'bg-warning':'bg-secondary'}">${data.status}</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success saveBtn me-1"><i class="mdi mdi-content-save"></i></button>
                        <button class="btn btn-sm btn-danger deleteBtn"><i class="mdi mdi-delete"></i></button>
                    </td>
                `;
                table.appendChild(newRow);
                rows.push(newRow);
                renderTable();

                form.reset();
                addModal.hide();
            }
        })
        .catch(err=>console.error(err));
    });

    // Sauvegarder / Supprimer avec contrôle
    table.addEventListener('click', e=>{
        const row = e.target.closest('tr');
        const id = row.dataset.id;
        if(e.target.closest('.saveBtn')){
            const userName = row.querySelector('.user-name-span').textContent.trim();
            const age = parseInt(row.querySelector('.age-input').value);
            const weight = parseFloat(row.querySelector('.weight-input').value);
            const challengeId = row.querySelector('.challenge-select').value;

            if(!validateRow(userName, age, weight, challengeId)) return;

            const data = { challenge_id: challengeId, age, weight };
            fetch(`/participations/${id}`,{
                method:'PUT',
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
                body: JSON.stringify(data)
            })
            .then(res=>res.json())
            .then(res=>{
                if(res.success) alert('Enregistré avec succès');
                else alert(res.error || JSON.stringify(res.errors));
            });
        }

        if(e.target.closest('.deleteBtn')){
            if(confirm('Supprimer cette participation ?')){
                fetch(`/participations/${id}`,{
                    method:'DELETE',
                    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                })
                .then(res=>res.json())
                .then(res=>{
                    if(res.success){
                        row.remove();
                        rows = rows.filter(r=>r.dataset.id != id);
                        renderTable();
                    } else alert(res.error);
                });
            }
        }
    });
});
</script>

<style>
.table-hover tbody tr:hover { background-color: #f8f9fa; transition: 0.3s; }
.card { border-radius: 15px; }
#searchInput, #ageFilter { border-radius: 50px; }
.btn { border-radius: 0.5rem; transition: 0.3s; }
.btn:hover { opacity: 0.9; }
.badge { padding: 0.45em 0.7em; font-size: 0.85rem; }
.filter-panel { animation: slideDown 0.3s ease-in-out; }
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

@endsection
