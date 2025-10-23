@extends('layout.master')

@section('title','Participations')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>📝 Participations</h4>
            <button class="btn btn-success btn-sm" id="addParticipationBtn">
                <i class="mdi mdi-plus"></i> Add Participation
            </button>
        </div>

        {{-- Search --}}
        <div class="d-flex justify-content-center mb-4 flex-wrap align-items-center gap-3">
            <input type="text" id="searchInput" class="form-control w-50 text-center shadow-sm" placeholder="Search by challenge or user">
        </div>

        {{-- Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover mb-0 rounded-3" id="participationTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Challenge</th>
                            <th>User</th>
                            <th>Age</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participations as $index => $part)
                        <tr data-id="{{ $part->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <select class="form-select form-select-sm challenge-select">
                                    @foreach($challenges as $challenge)
                                        <option value="{{ $challenge->id }}" @if($challenge->id == $part->challenge_id) selected @endif>{{ $challenge->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm user-select">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == $part->user_id) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control form-control-sm age-input" value="{{ $part->age }}"></td>
                            <td><input type="number" step="0.1" class="form-control form-control-sm weight-input" value="{{ $part->weight }}"></td>
                            <td>
                                <span class="badge @if($part->status=='approved') bg-success 
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
                <div class="d-flex justify-content-center mt-3 gap-3">
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Prev</button>
                    <span id="pageInfo"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const searchInput = document.getElementById('searchInput');
    const table = document.querySelector('#participationTable tbody');
    let rows = Array.from(table.rows);
    const rowsPerPage = 5;
    let currentPage = 1;

    const challengesList = @json($challenges);
    const usersList = @json($users);

    function renderTable() {
        const query = searchInput.value.toLowerCase();
        let filtered = rows.filter(row => {
            const challenge = row.querySelector('.challenge-select option:checked').textContent.toLowerCase();
            const user = row.querySelector('.user-select option:checked').textContent.toLowerCase();
            return challenge.includes(query) || user.includes(query);
        });

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if(currentPage > totalPages) currentPage = totalPages || 1;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);

        table.innerHTML = '';
        paginated.forEach((row, idx) => {
            row.cells[0].textContent = start + idx + 1;
            table.appendChild(row);
        });

        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;
    }

    searchInput.addEventListener('input', () => { currentPage=1; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if(currentPage>1){ currentPage--; renderTable(); }});
    document.getElementById('nextPage').addEventListener('click', () => { 
        const totalPages = Math.ceil(rows.filter(row => {
            const challenge = row.querySelector('.challenge-select option:checked').textContent.toLowerCase();
            const user = row.querySelector('.user-select option:checked').textContent.toLowerCase();
            return challenge.includes(searchInput.value.toLowerCase()) || user.includes(searchInput.value.toLowerCase());
        }).length / rowsPerPage);
        if(currentPage < totalPages){ currentPage++; renderTable(); }
    });

    renderTable();

    // Delete Participation
    table.addEventListener('click', e => {
        if(e.target.closest('.deleteBtn')){
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            if(confirm('Delete this participation?')){
                fetch(`/participations/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
                .then(() => {
                    row.remove();
                    rows = rows.filter(r => r !== row);
                    renderTable();
                });
            }
        }
    });

    // Add Participation
    document.getElementById('addParticipationBtn').addEventListener('click', () => {
        const newRow = document.createElement('tr');

        let challengesOptions = '';
        challengesList.forEach(c => { challengesOptions += `<option value="${c.id}">${c.name}</option>`; });

        let usersOptions = '';
        usersList.forEach(u => { usersOptions += `<option value="${u.id}">${u.name}</option>`; });

        newRow.innerHTML = `
            <td></td>
            <td><select class="form-select form-select-sm challenge-select">${challengesOptions}</select></td>
            <td><select class="form-select form-select-sm user-select">${usersOptions}</select></td>
            <td><input type="number" class="form-control form-control-sm age-input"></td>
            <td><input type="number" step="0.1" class="form-control form-control-sm weight-input"></td>
            <td><span class="badge bg-warning">Pending</span></td>
            <td class="text-center">
                <button class="btn btn-sm btn-success saveBtn me-1"><i class="mdi mdi-content-save"></i></button>
                <button class="btn btn-sm btn-danger deleteBtn"><i class="mdi mdi-delete"></i></button>
            </td>
        `;
        table.prepend(newRow);
        rows.unshift(newRow);
        renderTable();
    });

    // Save Participation (Add or Update)
    table.addEventListener('click', e => {
        if(e.target.closest('.saveBtn')){
            const row = e.target.closest('tr');
            const id = row.dataset.id || null;
            const challenge_id = row.querySelector('.challenge-select').value;
            const user_id = row.querySelector('.user-select').value;
            const age = row.querySelector('.age-input').value;
            const weight = row.querySelector('.weight-input').value;

            const payload = { challenge_id, user_id, age, weight };

            let url = id ? `/participations/${id}` : '/participations';
            let method = id ? 'PUT' : 'POST';

            fetch(url, {
                method,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}','Content-Type':'application/json'},
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                row.dataset.id = data.id;
                row.querySelector('.saveBtn').remove();
            });
        }
    });

});
</script>

<style>
.table-hover tbody tr:hover { background-color: #f5f5f5; transition: 0.3s; }
.card { border-radius: 15px; }
#searchInput { border-radius:50px; }
.btn { border-radius: 0.5rem; transition: 0.3s; }
.btn:hover { opacity: 0.9; }
.badge { padding: 0.4em 0.65em; font-size: 0.85rem; }
</style>
@endsection
