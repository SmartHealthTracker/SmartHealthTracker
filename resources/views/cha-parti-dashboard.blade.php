@extends('layout.master')

@section('title', 'Challenge & Participation Dashboard')

@section('content')
<div class="row g-4">
    <!-- Summary Cards -->
    @php
        $cards = [
            ['icon' => 'mdi-calendar-multiple', 'color' => 'primary', 'title' => 'Total Challenges', 'count' => $totalChallenges, 'desc' => 'All event challenges'],
            ['icon' => 'mdi-account-multiple', 'color' => 'success', 'title' => 'Total Participations', 'count' => $totalParticipations, 'desc' => 'Participant entries'],
            ['icon' => 'mdi-check-circle', 'color' => 'info', 'title' => 'Approved', 'count' => $approved, 'desc' => 'Valid participations'],
            ['icon' => 'mdi-timer-sand', 'color' => 'warning', 'title' => 'Pending', 'count' => $pending, 'desc' => 'Awaiting approval']
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 hover-shadow">
            <div class="card-body">
                <i class="mdi {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size:2.2rem;"></i>
                <h5 class="mt-3 fw-semibold">{{ $card['title'] }}</h5>
                <h3 class="fw-bold">{{ $card['count'] }}</h3>
                <small class="text-muted">{{ $card['desc'] }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Section -->
<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3">📈 Challenges Created Per Month</h4>
                <canvas id="challengesChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3">🏆 Top 5 Challenges by Participation</h4>
                <canvas id="topChallengesChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Challenge Timetable -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">🗓️ Challenge Timetable</h4>
            <a href="{{ route('challenges.exportPdf') }}" class="p-0" title="Export as PDF">
                <i class="mdi mdi-file-pdf-outline text-danger" style="font-size:1.8rem;"></i>
            </a>
        </div>

        <!-- Search & Sort Controls -->
        <div class="d-flex justify-content-center mb-4 flex-wrap align-items-center gap-3">
            <input type="text" id="searchInput" class="form-control w-50 text-center shadow-sm" placeholder="Search by first letter...">
            <div class="btn-group shadow-sm" role="group">
                <button class="btn btn-outline-primary btn-sm px-4" id="sortAsc">A-Z</button>
                <button class="btn btn-outline-primary btn-sm px-4" id="sortDesc">Z-A</button>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover mb-0 rounded-3" id="challengeTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Challenge Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timetableChallenges as $index => $challenge)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="challenge-name">{{ $challenge['title'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($challenge['start'])->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($challenge['end'])->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center align-items-center mt-3 gap-3 p-2">
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Prev</button>
                    <span id="pageInfo"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Redesigned Lifetime Health Tip Assistant -->
<div id="lifetip-chatbot" class="chatbot-card">
    <div class="chatbot-header">
        💡 Lifetime Health Tip Assistant
    </div>
    <div class="chatbot-body">
        <div id="lifetip-message" class="chatbot-message">
            Ask me for a fitness plan, healthy recipe, or a lifetime tip!
        </div>
        <div class="chatbot-controls">
            <input type="text" id="lifetip-input" placeholder="Write your question or type 'fitness' / 'recipe'..." />
            <button id="lifetip-send">Get Tip</button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Charts
    new Chart(document.getElementById('challengesChart'), {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Challenges Created',
                data: @json($counts),
                borderColor: '#4b49ac',
                backgroundColor: 'rgba(75,73,172,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const topChallenges = @json($topChallenges);
    new Chart(document.getElementById('topChallengesChart'), {
        type: 'bar',
        data: {
            labels: topChallenges.map(c => c.name),
            datasets: [{
                label: 'Participations',
                data: topChallenges.map(c => c.participations_count),
                backgroundColor: 'rgba(255,99,132,0.6)'
            }]
        },
        options: { responsive: true }
    });

    // Table Search + Sort + Pagination
    const table = document.querySelector('#challengeTable tbody');
    const allRows = Array.from(table.rows);
    const rowsPerPage = 5;
    let currentPage = 1;
    let currentSort = 'asc';

    function renderTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        let filtered = allRows.filter(r =>
            r.querySelector('.challenge-name').textContent.toLowerCase().startsWith(query)
        );

        filtered.sort((a, b) => {
            const nameA = a.querySelector('.challenge-name').textContent.toLowerCase();
            const nameB = b.querySelector('.challenge-name').textContent.toLowerCase();
            return currentSort === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
        });

        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        currentPage = Math.min(currentPage, totalPages || 1);
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start + rowsPerPage);

        table.innerHTML = '';
        paginated.forEach((r, i) => {
            r.cells[0].textContent = start + i + 1;
            table.appendChild(r);
        });

        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;
    }

    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; renderTable(); });
    document.getElementById('sortAsc').addEventListener('click', () => { currentSort = 'asc'; renderTable(); });
    document.getElementById('sortDesc').addEventListener('click', () => { currentSort = 'desc'; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if(currentPage>1) currentPage--; renderTable(); });
    document.getElementById('nextPage').addEventListener('click', () => { const totalPages = Math.ceil(allRows.length / rowsPerPage); if(currentPage<totalPages) currentPage++; renderTable(); });
    renderTable();

    // Chatbot
    const tipInput = document.getElementById('lifetip-input');
    const tipSend = document.getElementById('lifetip-send');
    const tipMessage = document.getElementById('lifetip-message');

    async function sendTip(message) {
        tipMessage.textContent = '💭 Thinking...';
        try {
            const response = await fetch('{{ route('chatbot.reply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });
            const data = await response.json();
            tipMessage.textContent = data.reply;
        } catch {
            tipMessage.textContent = '⚠️ Connection error. Try again!';
        }
    }

    tipSend.addEventListener('click', () => {
        const msg = tipInput.value.trim();
        if(msg){ sendTip(msg); tipInput.value = ''; }
    });

    tipInput.addEventListener('keypress', (e) => {
        if(e.key === 'Enter'){ e.preventDefault(); tipSend.click(); }
    });
});
</script>

<!-- Styles -->
<style>
/* Cards */
.hover-shadow:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.15); transition: 0.3s; }
.table-hover tbody tr:hover { background-color: #f9f9f9; transition: 0.3s; }
#searchInput { border-radius: 30px; }
.btn-group button { border-radius: 25px; }
.card { border-radius: 15px; }

/* Redesigned Chatbot */
.chatbot-card {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    width: 420px;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 12px 28px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    z-index: 999;
    overflow: hidden;
    font-family: 'Segoe UI', sans-serif;
}

.chatbot-header {
    background: linear-gradient(135deg, #ff9800, #ffb74d);
    color: #fff;
    text-align: center;
    font-weight: 600;
    font-size: 1.2rem;
    padding: 16px;
}

.chatbot-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.chatbot-message {
    background: #f1f1f1;
    border-radius: 15px;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    text-align: center;
    font-size: 0.95rem;
    color: #333;
    box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
}

.chatbot-controls {
    display: flex;
    gap: 10px;
}

#lifetip-input {
    flex: 1;
    border-radius: 25px;
    border: 1px solid #ccc;
    padding: 10px 15px;
    font-size: 0.95rem;
}

#lifetip-send {
    border-radius: 25px;
    background: #ff9800;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

#lifetip-send:hover { background: #e68a00; }

@media (max-width: 450px) {
    .chatbot-card { width: 90%; left: 50%; transform: translateX(-50%); bottom: 20px; }
}
</style>

@endsection
