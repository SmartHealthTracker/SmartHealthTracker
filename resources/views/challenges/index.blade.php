@extends('layout.master')

@section('title', 'Tableau de bord Challenges & Participations')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>🗓️ Challenges</h4>
            <button class="btn btn-success btn-sm" id="addChallengeBtn">
                <i class="mdi mdi-plus"></i> Ajouter un Challenge
            </button>
        </div>

        {{-- Recherche & Tri --}}
        <div class="d-flex justify-content-center mb-4 flex-wrap align-items-center gap-3">
            <input type="text" id="searchInput" class="form-control w-50 text-center shadow-sm" placeholder="Rechercher par première lettre">
            <div class="btn-group shadow-sm">
                <button class="btn btn-outline-primary btn-sm px-4" id="sortAsc">A-Z</button>
                <button class="btn btn-outline-primary btn-sm px-4" id="sortDesc">Z-A</button>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover mb-0 rounded-3" id="challengeTable">
                    <thead class="table-light">
                        <tr>
                            <th style="display:none;">#</th>
                            <th>Nom du Challenge</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Participations</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetableChallenges as $challenge)
                        <tr data-id="{{ $challenge['id'] }}">
                            <td style="display:none;">{{ $loop->iteration }}</td>
                            <td class="editable challenge-name">{{ $challenge['title'] }}</td>
                            <td class="editable start-date">{{ \Carbon\Carbon::parse($challenge['start'])->format('d/m/Y') }}</td>
                            <td class="editable end-date">{{ \Carbon\Carbon::parse($challenge['end'])->format('d/m/Y') }}</td>
                            <td class="participation text-center">{{ $challenge['participations_count'] ?? 0 }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger deleteBtn"><i class="mdi mdi-delete"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3 gap-3">
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Précédent</button>
                    <span id="pageInfo"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.querySelector('#challengeTable tbody');
    let rows = Array.from(table.rows);
    const rowsPerPage = 5;
    let currentPage = 1;
    let currentSort = 'asc';

    const formatDateToDisplay = d => {
        const [y, m, day] = d.split('-');
        return `${day}/${m}/${y}`;
    };

    const formatDateToInput = d => {
        const [day, m, y] = d.split('/');
        return `${y}-${m}-${day}`;
    };

    // ✅ Contrôle de saisie pour le nom
    function validateRow(title, startDate, endDate) {
        // Autoriser uniquement les lettres et espaces (y compris accents)
        const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
        if(!title.trim()) {
            alert("Le nom du challenge ne peut pas être vide !");
            return false;
        }
        if(!nameRegex.test(title)) {
            alert("Le nom du challenge ne peut contenir que des lettres et des espaces !");
            return false;
        }
        if(new Date(startDate) > new Date(endDate)) {
            alert("La date de début doit être antérieure ou égale à la date de fin !");
            return false;
        }
        return true;
    }

    function renderTable() {
        const query = searchInput.value.toLowerCase();
        let filtered = rows.filter(r =>
            r.querySelector('.challenge-name').textContent.toLowerCase().startsWith(query)
        );

        filtered.sort((a, b) => {
            let nameA = a.querySelector('.challenge-name').textContent.toLowerCase();
            let nameB = b.querySelector('.challenge-name').textContent.toLowerCase();
            return currentSort === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
        });

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);

        table.innerHTML = '';
        paginated.forEach(row => table.appendChild(row));

        document.getElementById('pageInfo').textContent = `Page ${currentPage} sur ${totalPages || 1}`;
    }

    searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
    document.getElementById('sortAsc').addEventListener('click', () => { currentSort = 'asc'; renderTable(); });
    document.getElementById('sortDesc').addEventListener('click', () => { currentSort = 'desc'; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
    document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        if (currentPage < totalPages) { currentPage++; renderTable(); }
    });

    renderTable();

    // Édition inline avec contrôle de saisie
    table.addEventListener('click', e => {
        const target = e.target;
        if (target.classList.contains('editable')) {
            const oldValue = target.textContent;
            const input = document.createElement('input');
            input.type = target.classList.contains('start-date') || target.classList.contains('end-date') ? 'date' : 'text';
            input.value = input.type === 'date' ? formatDateToInput(oldValue) : oldValue;
            input.className = 'form-control form-control-sm';
            target.textContent = '';
            target.appendChild(input);
            input.focus();

            input.addEventListener('blur', () => {
                const row = target.closest('tr');
                const id = row.dataset.id;
                if(input.type === 'date') target.textContent = formatDateToDisplay(input.value);
                else target.textContent = input.value;

                let title = row.querySelector('.challenge-name').textContent;
                let startDate = row.querySelector('.start-date').textContent.split('/').reverse().join('-');
                let endDate = row.querySelector('.end-date').textContent.split('/').reverse().join('-');

                if(!validateRow(title, startDate, endDate)) {
                    target.textContent = oldValue; // revenir à l'ancienne valeur
                    return;
                }

                fetch(`/challenges/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        start_date: startDate,
                        end_date: endDate
                    })
                });
            });
        }
    });

    // Suppression
    table.addEventListener('click', e => {
        if (e.target.closest('.deleteBtn')) {
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            if (confirm('Supprimer ce challenge ?')) {
                fetch(`/challenges/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => {
                    row.remove();
                    rows = rows.filter(r => r !== row);
                    renderTable();
                });
            }
        }
    });

    // Ajouter un challenge avec validation
    document.getElementById('addChallengeBtn').addEventListener('click', () => {
        const today = new Date().toISOString().split('T')[0];
        fetch(`/challenges`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: 'NouveauChallenge', start_date: today, end_date: today })
        }).then(res => res.json()).then(data => {
            if(!validateRow(data.title, data.start_date, data.end_date)) return;
            const newRow = document.createElement('tr');
            newRow.dataset.id = data.id;
            newRow.innerHTML = `
                <td style="display:none;"></td>
                <td class="editable challenge-name">${data.title}</td>
                <td class="editable start-date">${formatDateToDisplay(data.start_date)}</td>
                <td class="editable end-date">${formatDateToDisplay(data.end_date)}</td>
                <td class="participation text-center">0</td>
                <td class="text-center"><button class="btn btn-sm btn-danger deleteBtn"><i class="mdi mdi-delete"></i></button></td>
            `;
            table.prepend(newRow);
            rows.unshift(newRow);
            renderTable();
        });
    });
});
</script>

<style>
.table-hover tbody tr:hover { background-color:#f5f5f5; transition:0.3s; }
#searchInput { border-radius:50px; }
.btn-group button { border-radius:25px; }
.card { border-radius:15px; }
.editable { cursor:pointer; }
.participation { background-color:#f8f9fa; }
</style>
@endsection
