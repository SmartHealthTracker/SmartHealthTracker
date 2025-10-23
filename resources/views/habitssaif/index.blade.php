@extends('layout.master')

@push('styles')
<style>
    :root {
        --primary: #00b0ff;
        --secondary: #00e676;
        --accent: #ff4081;
        --dark: #1a237e;
        --light: #f8f9fa;
        --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --sport-gradient: linear-gradient(135deg, #00b0ff 0%, #00e676 100%);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .sport-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 30px;
        background: var(--gradient);
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
    }

    .sport-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.pexels.com/photos/1552242/pexels-photo-1552242.jpeg') center/cover;
        opacity: 0.1;
    }

    .sport-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .sport-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
    }

    .card-view {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .table-view {
        display: none;
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .table-view th, .table-view td {
        padding: 16px 20px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }

    .table-view th {
        background: var(--sport-gradient);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table-view tbody tr {
        transition: all 0.3s ease;
    }

    .table-view tbody tr:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .sport-card {
        position: relative;
        width: 100%;
        height: 280px;
        border-radius: 20px;
        overflow: hidden;
        color: white;
        background-size: cover;
        background-position: center;
        box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }

    .sport-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .sport-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        z-index: 1;
    }

    .card-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .card-category {
        background: var(--accent);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-icon {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .card-description {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .card-target {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        padding: 8px 15px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        font-weight: 600;
    }

    .controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .mode-switch {
        display: flex;
        background: #f8f9fa;
        border-radius: 25px;
        padding: 6px;
        gap: 2px;
    }

    .mode-btn {
        padding: 10px 24px;
        border: none;
        background: transparent;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .mode-btn.active {
        background: var(--sport-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(0,176,255,0.4);
    }

    .stats-info {
        display: flex;
        gap: 20px;
        font-size: 0.9rem;
        color: #666;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-top: 40px;
    }

    .page-number {
        padding: 10px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        text-decoration: none;
        color: #666;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .page-number.active {
        background: var(--sport-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(0,176,255,0.3);
    }

    .page-number:hover:not(.active) {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Modal Sportif */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        position: relative;
        color: #333;
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: #666;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: var(--accent);
    }

    .modal-title {
        color: var(--dark);
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 15px;
        border-bottom: 3px solid var(--secondary);
        padding-bottom: 10px;
    }

    .modal-info {
        margin: 20px 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        padding: 8px 0;
    }

    .info-label {
        font-weight: 600;
        color: var(--dark);
        min-width: 100px;
    }

    .info-value {
        color: #666;
    }

    .modal-qr {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    @media (max-width: 768px) {
        .controls {
            flex-direction: column;
            gap: 20px;
        }

        .card-view {
            grid-template-columns: 1fr;
        }

        .stats-info {
            flex-wrap: wrap;
            justify-content: center;
        }

        .sport-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="sport-header">
        <h1 class="sport-title">🏆 Mes Habitudes Sportives</h1>
        <p class="sport-subtitle">Optimisez vos performances et suivez votre progression</p>

    </div>
<a href="{{ route('habitssaif.create') }}"
   class="btn btn-primary"
   title="Créer un nouvel habit">
    <i class="fas fa-plus"></i> Créer
</a>
    <div class="controls">
        <div class="mode-switch">
            <button class="mode-btn active" id="cardModeBtn">
                <i class="fas fa-th-large"></i> Vue Cartes
            </button>
            <button class="mode-btn" id="tableModeBtn">
                <i class="fas fa-table"></i> Vue Tableau
            </button>
        </div>
        <div class="stats-info">
            <div class="stat-item">
                <i class="fas fa-dumbbell"></i>
                <span>{{ $habits->total() }} Activités</span>
            </div>
            <div class="stat-item">
                <i class="fas fa-eye"></i>
                <span>Affichage {{ $habits->firstItem() ?? 0 }}–{{ $habits->lastItem() ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Vue Cartes -->
    <div class="card-view" id="cardView">
        @foreach($habits as $habit)
            @php
                $sportImages = [
                    'Course' => 'https://images.pexels.com/photos/2359223/pexels-photo-2359223.jpeg',
                    'Musculation' => 'https://images.pexels.com/photos/1229356/pexels-photo-1229356.jpeg',
                    'Yoga' => 'https://images.pexels.com/photos/1812964/pexels-photo-1812964.jpeg',
                    'Cyclisme' => 'https://images.pexels.com/photos/248547/pexels-photo-248547.jpeg',
                    'Natation' => 'https://images.pexels.com/photos/261265/pexels-photo-261265.jpeg',
                    'Marche' => 'https://images.pexels.com/photos/1365425/pexels-photo-1365425.jpeg',
                    'Football' => 'https://images.pexels.com/photos/274422/pexels-photo-274422.jpeg',
                    'Basketball' => 'https://images.pexels.com/photos/1752757/pexels-photo-1752757.jpeg',
                    'Tennis' => 'https://images.pexels.com/photos/209977/pexels-photo-209977.jpeg',
                ];

                $sportIcons = [
                    'Course' => '🏃',
                    'Musculation' => '💪',
                    'Yoga' => '🧘',
                    'Cyclisme' => '🚴',
                    'Natation' => '🏊',
                    'Marche' => '🚶',
                    'Football' => '⚽',
                    'Basketball' => '🏀',
                    'Tennis' => '🎾',
                ];

                $bg = $sportImages[$habit->title] ?? 'https://images.pexels.com/photos/1552242/pexels-photo-1552242.jpeg';
                $icon = $sportIcons[$habit->title] ?? '🏆';
            @endphp
            <div class="sport-card"
                style="background-image:url('{{ $bg }}');"
                data-title="{{ $habit->title }}"
                data-description="{{ $habit->description }}"
                data-category="{{ $habit->category }}"
                data-target="{{ $habit->target_value }}"
                data-unit="{{ $habit->unit }}"
                data-user="{{ $habit->user->name ?? 'N/A' }}"
                data-created="{{ $habit->created_at->format('d/m/Y') }}"
                data-icon="{{ $icon }}">
                <div class="card-content">
                    <div class="card-header">
                        <div class="card-category">{{ $habit->category }}</div>
                        <div class="card-icon">{{ $icon }}</div>
                    </div>
                    <div>
                        <h3 class="card-title">{{ $habit->title }}</h3>
                        <p class="card-description">{{ Str::limit($habit->description, 80) }}</p>
                        <div class="card-target">
                            <i class="fas fa-bullseye"></i>
                            <span>Objectif: {{ $habit->target_value }} {{ $habit->unit }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Vue Tableau -->
    <table class="table-view" id="tableView">
        <thead>
            <tr>
                <th>Activité</th>
                <th>Catégorie</th>
                <th>Objectif</th>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Acttion<th>
            </tr>
        </thead>
        <tbody>
            @foreach($habits as $habit)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @php
                            $icons = ['🏃','💪','🧘','🚴','🏊','🚶','⚽','🏀','🎾'];
                            $icon = $icons[array_rand($icons)];
                        @endphp
                        <span style="font-size: 1.2rem;">{{ $icon }}</span>
                        <strong>{{ $habit->title }}</strong>
                    </div>
                </td>
                <td>
                    <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                        {{ $habit->category }}
                    </span>
                </td>
                <td>
                    <strong style="color: var(--secondary);">{{ $habit->target_value }} {{ $habit->unit }}</strong>
                </td>
                <td>{{ $habit->user->name ?? 'N/A' }}</td>
                <td>{{ $habit->created_at->format('d/m/Y') }}</td>
                <td>
   <td>
    <a href="{{ route('habitssaif.show', $habit) }}"
       class="sport-btn btn-primary"
       style="padding: 6px 12px; font-size: 0.85rem;">
       <i class="fas fa-eye"></i> Voir Détails
    </a>
</td>

            </tr>
            @endforeach
        </tbody>
    </table>

    @if($habits->hasPages())
    <div class="pagination">
        @if($habits->onFirstPage())
            <span class="page-number" style="opacity: 0.5;">⬅ Précédent</span>
        @else
            <a href="{{ $habits->previousPageUrl() }}" class="page-number">⬅ Précédent</a>
        @endif

        <span class="page-number active">{{ $habits->currentPage() }}</span>

        @if($habits->hasMorePages())
            <a href="{{ $habits->nextPageUrl() }}" class="page-number">Suivant ➡</a>
        @else
            <span class="page-number" style="opacity: 0.5;">Suivant ➡</span>
        @endif
    </div>
    @endif

    <!-- Modal -->
    <div class="modal" id="habitModal">
        <div class="modal-content">
            <span class="modal-close" id="modalClose">&times;</span>
            <h2 class="modal-title" id="modalTitle"></h2>

            <div class="modal-info">
                <div class="info-item">
                    <span class="info-label">📝 Description:</span>
                    <span class="info-value" id="modalDescription"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">🏷️ Catégorie:</span>
                    <span class="info-value" id="modalCategory" style="color: var(--primary); font-weight: 600;"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">🎯 Objectif:</span>
                    <span class="info-value" id="modalTarget" style="color: var(--secondary); font-weight: 600;"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">👤 Utilisateur:</span>
                    <span class="info-value" id="modalUser"></span>
                </div>
                <div class="info-item">
                    <span class="info-label">📅 Créée le:</span>
                    <span class="info-value" id="modalCreated"></span>
                </div>
            </div>

            <div class="modal-qr" id="modalQr"></div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('habitModal');
    const modalClose = document.getElementById('modalClose');
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');
    const cardBtn = document.getElementById('cardModeBtn');
    const tableBtn = document.getElementById('tableModeBtn');

    // Bascule entre vues
    cardBtn.addEventListener('click', () => {
        cardView.style.display = 'grid';
        tableView.style.display = 'none';
        cardBtn.classList.add('active');
        tableBtn.classList.remove('active');
    });

    tableBtn.addEventListener('click', () => {
        cardView.style.display = 'none';
        tableView.style.display = 'table';
        tableBtn.classList.add('active');
        cardBtn.classList.remove('active');
    });

    // Ouverture du modal
    document.querySelectorAll('.sport-card').forEach(card => {
        card.addEventListener('click', () => {
            const { title, description, category, target, unit, user, created, icon } = card.dataset;

            document.getElementById('modalTitle').textContent = `${icon} ${title}`;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalCategory').textContent = category;
            document.getElementById('modalTarget').textContent = `${target} ${unit}`;
            document.getElementById('modalUser').textContent = user;
            document.getElementById('modalCreated').textContent = created;

            const qrData = encodeURIComponent(
                `🏋️‍♂️ Habitude Sportive: ${title}\n` +
                `📂 Catégorie: ${category}\n` +
                `🎯 Objectif: ${target} ${unit}\n` +
                `📝 Description: ${description}\n` +
                `👤 Utilisateur: ${user}\n` +
                `📅 Créée le: ${created}`
            );

            document.getElementById('modalQr').innerHTML = `
                <p style="margin-bottom: 15px; color: #666; font-weight: 600;">QR Code de l'activité</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${qrData}&bgcolor=ffffff&color=00b0ff"
                     alt="QR Code"
                     style="border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.15); border: 2px solid #f0f0f0;">
            `;

            modal.style.display = 'flex';
        });
    });

    // Fermeture du modal
    modalClose.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>
@endpush
