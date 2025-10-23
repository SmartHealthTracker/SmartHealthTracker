@extends('layout.master')

@section('title', 'Tableau de Bord des Challenges & Participations')

@section('content')
<div class="row g-4">
    <!-- Cartes de résumé -->
    @php
        $cards = [
            ['icon' => 'mdi-calendar-multiple', 'color' => 'primary', 'title' => 'Total des Challenges', 'count' => $totalChallenges, 'desc' => 'Tous les challenges d\'événements'],
            ['icon' => 'mdi-account-multiple', 'color' => 'success', 'title' => 'Total des Participations', 'count' => $totalParticipations, 'desc' => 'Entrées des participants'],
            ['icon' => 'mdi-check-circle', 'color' => 'info', 'title' => 'Approuvées', 'count' => $approved, 'desc' => 'Participations validées'],
            ['icon' => 'mdi-close-circle', 'color' => 'warning', 'title' => 'Rejetées', 'count' => $rejected, 'desc' => 'Participations rejetées']
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

<!-- Section des graphiques -->
<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3">📈 Challenges Créés par Mois</h4>
                <canvas id="challengesChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title mb-3">🏆 Top 5 Challenges par Participation</h4>
                <canvas id="topChallengesChart" height="160"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Emploi du temps des Challenges -->
<div class="row mt-5">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">🗓️ Emploi du Temps des Challenges</h4>
            <a href="{{ route('challenges.exportPdf') }}" class="p-0" title="Exporter en PDF">
                <i class="mdi mdi-file-pdf-outline text-danger" style="font-size:1.8rem;"></i>
            </a>
        </div>

        <!-- Contrôles de recherche et tri -->
        <div class="d-flex justify-content-center mb-4 flex-wrap align-items-center gap-3">
            <input type="text" id="searchInput" class="form-control w-50 text-center shadow-sm" placeholder="Rechercher par première lettre...">
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
                            <th>Nom du Challenge</th>
                            <th>Date de Début</th>
                            <th>Date de Fin</th>
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
                    <button id="prevPage" class="btn btn-outline-secondary btn-sm">Précédent</button>
                    <span id="pageInfo"></span>
                    <button id="nextPage" class="btn btn-outline-secondary btn-sm">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assistant Conseil Santé -->
<div class="row mt-5">
    <div class="col-md-12">
        <h4 class="fw-bold mb-3">💡 Assistant Conseils Santé</h4>
        <div class="d-flex gap-3">
            <!-- Colonne Input -->
            <div style="flex:1;">
                <div class="chatbot-controls">
                    <input type="text" id="lifetip-input" class="form-control" placeholder="Tapez 'fitness' ou 'recette'" />
                    <button id="lifetip-send" class="btn btn-warning mt-2">Obtenir un Conseil</button>
                </div>
            </div>

            <!-- Colonne des cartes de conseils -->
            <div style="flex:1;" id="lifetip-tips" class="tip-cards"></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialisation des graphiques
    new Chart(document.getElementById('challengesChart'), {
        type: 'line',
        data: { labels: @json($months), datasets: [{ label: 'Challenges Créés', data: @json($counts), borderColor: '#4b49ac', backgroundColor: 'rgba(75,73,172,0.2)', fill: true, tension: 0.3 }] },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const topChallenges = @json($topChallenges);
    new Chart(document.getElementById('topChallengesChart'), {
        type: 'bar',
        data: { labels: topChallenges.map(c => c.name), datasets: [{ label: 'Participations', data: topChallenges.map(c => c.participations_count), backgroundColor: 'rgba(255,99,132,0.6)' }] },
        options: { responsive: true }
    });

    // Recherche, tri et pagination
    const table = document.querySelector('#challengeTable tbody');
    const allRows = Array.from(table.rows);
    const rowsPerPage = 5;
    let currentPage = 1;
    let currentSort = 'asc';

    function renderTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        let filtered = allRows.filter(r => r.querySelector('.challenge-name').textContent.toLowerCase().startsWith(query));
        filtered.sort((a,b) => currentSort==='asc'? a.querySelector('.challenge-name').textContent.toLowerCase().localeCompare(b.querySelector('.challenge-name').textContent.toLowerCase()) : b.querySelector('.challenge-name').textContent.toLowerCase().localeCompare(a.querySelector('.challenge-name').textContent.toLowerCase()));
        const totalPages = Math.ceil(filtered.length / rowsPerPage);
        currentPage = Math.min(currentPage, totalPages || 1);
        const start = (currentPage - 1) * rowsPerPage;
        const paginated = filtered.slice(start, start+rowsPerPage);
        table.innerHTML = '';
        paginated.forEach((r,i) => { r.cells[0].textContent = start+i+1; table.appendChild(r); });
        document.getElementById('pageInfo').textContent = `Page ${currentPage} sur ${totalPages || 1}`;
    }

    document.getElementById('searchInput').addEventListener('input', () => { currentPage=1; renderTable(); });
    document.getElementById('sortAsc').addEventListener('click', () => { currentSort='asc'; renderTable(); });
    document.getElementById('sortDesc').addEventListener('click', () => { currentSort='desc'; renderTable(); });
    document.getElementById('prevPage').addEventListener('click', () => { if(currentPage>1) currentPage--; renderTable(); });
    document.getElementById('nextPage').addEventListener('click', () => { const totalPages=Math.ceil(allRows.length/rowsPerPage); if(currentPage<totalPages) currentPage++; renderTable(); });
    renderTable();

    // Assistant Conseil Santé
    const tipInput = document.getElementById('lifetip-input');
    const tipSend = document.getElementById('lifetip-send');
    const tipsContainer = document.getElementById('lifetip-tips');

    async function sendTip(message) {
        tipsContainer.innerHTML = '';
        try {
            const response = await fetch('{{ route('chatbot.reply') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message })
            });
            const data = await response.json();
            data.reply.forEach((tip,index) => {
                const card = document.createElement('div');
                card.classList.add('tip-card');
                card.style.animationDelay = `${index*0.1}s`;
                card.innerHTML = `
                    <h5 class="fw-bold">✨ ${tip.title}</h5>
                    <p>${tip.description}</p>
                `;
                tipsContainer.appendChild(card);
            });
        } catch { alert('Erreur de connexion !'); }
    }

    tipSend.addEventListener('click', ()=>{ const msg=tipInput.value.trim(); if(msg){ sendTip(msg); tipInput.value=''; } });
    tipInput.addEventListener('keypress', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); tipSend.click(); } });
});
</script>

<style>
/* Layout des cartes de conseils */
.tip-cards { display:flex; flex-direction:column; gap:12px; max-height:400px; overflow-y:auto; }
.tip-card {
    background: #fff3e0;
    border-left: 5px solid #ff9800;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    opacity: 0;
    transform: translateY(20px);
    animation: slideIn 0.4s forwards;
    transition: transform 0.3s, box-shadow 0.3s;
}
.tip-card:hover { transform: scale(1.03); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
@keyframes slideIn { to { opacity:1; transform:translateY(0); } }

#lifetip-input { border-radius:25px; }
#lifetip-send { border-radius:25px; transition:0.3s; }
#lifetip-send:hover { background:#e68a00; }
</style>
@endsection
