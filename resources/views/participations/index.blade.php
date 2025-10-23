@extends('layout.master')

@section('title', 'Participations')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">📝 Participations</h4>
            <button class="btn btn-success btn-sm shadow-sm" id="addParticipationBtn">
                <i class="mdi mdi-plus"></i> Add Participation
            </button>
        </div>

        {{-- Filter Section --}}
        <div class="card border-0 shadow-sm mb-4 rounded-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="mdi mdi-filter-outline"></i> Filters</h6>
                <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" id="toggleFilterBtn">
                    <i class="mdi mdi-chevron-down"></i> Show Filters
                </button>
            </div>
            <div class="card-body border-top bg-light p-4 filter-panel" id="filterPanel" style="display: none;">
                <div class="row g-3 align-items-center justify-content-center">

                    <div class="col-md-6">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="mdi mdi-magnify text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search by user name or challenge">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select id="ageFilter" class="form-select shadow-sm">
                            <option value="">Filter by Age</option>
                            <option value="0-25">0 - 25</option>
                            <option value="26-50">26 - 50</option>
                            <option value="51-65">51 - 65</option>
                        </select>
                    </div>

                    <div class="col-md-2 text-center">
                        <button id="clearFiltersBtn" class="btn btn-outline-secondary w-100">
                            <i class="mdi mdi-refresh"></i> Clear
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover mb-0" id="participationTable">
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
                                        <option value="{{ $challenge->id }}" @if($challenge->id == $part->challenge_id) selected @endif>
                                            {{ $challenge->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <span class="user-name-span">{{ $part->user->name }}</span>
                                <input type="text" class="form-control form-control-sm user-name-input d-none" value="{{ $part->user->name }}" readonly>
                            </td>
                            <td><input type="number" class="form-control form-control-sm age-input" value="{{ $part->age }}"></td>
                            <td><input type="number" step="0.1" class="form-control form-control-sm weight-input" value="{{ $part->weight }}"></td>
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
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Prev</button>
                    <span id="pageInfo" class="fw-bold"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('#participationTable tbody');
    let rows = Array.from(table.rows);
    const searchInput = document.getElementById('searchInput');
    const ageFilter = document.getElementById('ageFilter');
    const rowsPerPage = 5;
    let currentPage = 1;

    const loggedUserId = {{ auth()->id() }};
    const loggedUserName = '{{ auth()->user()->name }}';
    const challenges = @json($challenges);

    const toggleBtn = document.getElementById('toggleFilterBtn');
    const panel = document.getElementById('filterPanel');
    toggleBtn.addEventListener('click', () => {
        const isVisible = panel.style.display === 'block';
        panel.style.display = isVisible ? 'none' : 'block';
        toggleBtn.innerHTML = isVisible 
            ? '<i class="mdi mdi-chevron-down"></i> Show Filters'
            : '<i class="mdi mdi-chevron-up"></i> Hide Filters';
    });

    document.getElementById('clearFiltersBtn').addEventListener('click', () => {
        searchInput.value = '';
        ageFilter.value = '';
        renderTable();
    });

    function highlight(text, query) {
        if (!query) return text;
        const re = new RegExp(`^(${query})`, 'i');
        return text.replace(re, '<span class="highlight">$1</span>');
    }

    function renderTable() {
        const query = searchInput.value.toLowerCase();
        const ageRange = ageFilter.value;

        let filtered = rows.filter(row => {
            const challenge = row.querySelector('.challenge-select option:checked').textContent.toLowerCase();
            const user = row.querySelector('.user-name-span').textContent.toLowerCase();
            const age = parseInt(row.querySelector('.age-input').value || 0);

            const matchUser = user.startsWith(query);
            const matchChallenge = challenge.startsWith(query);
            let match = matchUser || matchChallenge;

            if (ageRange) {
                const [min, max] = ageRange.split('-').map(Number);
                match = match && age >= min && age <= max;
            }
            return match;
        });

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);

        table.innerHTML = '';
        paginated.forEach((row, idx) => {
            row.cells[0].textContent = start + idx + 1;

            // Highlight user and challenge
            const userSpan = row.querySelector('.user-name-span');
            userSpan.innerHTML = highlight(userSpan.textContent, query);

            table.appendChild(row);
        });

        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;
    }

    searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
    ageFilter.addEventListener('change', () => { currentPage = 1; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
    document.getElementById('nextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        if (currentPage < totalPages) { currentPage++; renderTable(); }
    });

    renderTable();
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
.highlight { background-color: #ffe58f; font-weight: 600; }
</style>
@endsection
