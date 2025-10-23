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

    .logs-header {
        text-align: center;
        margin-bottom: 40px;
        padding: 40px 30px;
        background: var(--gradient);
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
    }

    .logs-header::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url('https://images.pexels.com/photos/3768916/pexels-photo-3768916.jpeg') center/cover;
        opacity: 0.1;
    }

    .logs-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .logs-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
        margin-bottom: 20px;
    }

    .add-log-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 12px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }

    .add-log-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .logs-table-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }

    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logs-table th {
        background: var(--sport-gradient);
        color: white;
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .logs-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .logs-table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }

    .habit-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #e3f2fd;
        color: #1976d2;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 500;
    }

    .value-display {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--secondary);
        text-align: center;
    }

    .date-display {
        color: #666;
        font-weight: 500;
    }

    .actions-container {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .view-btn { background: #e8f5e8; color: #2e7d32; }
    .edit-btn { background: #e3f2fd; color: #1565c0; }
    .delete-btn { background: #ffebee; color: #c62828; }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: #e8f5e8;
        color: #2e7d32;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        border-left: 4px solid #00e676;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
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

    @media (max-width: 768px) {
        .logs-table { display: block; overflow-x: auto; }
        .actions-container { flex-direction: column; align-items: flex-start; }
        .stats-cards { grid-template-columns: 1fr; }
        .logs-title { font-size: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="logs-header">
        <h1 class="logs-title">📊 Journal des Activités Sportives</h1>
        <p class="logs-subtitle">Suivez votre progression et vos performances quotidiennes</p>
        <a href="{{ route('habit-logs.create') }}" class="add-log-btn">
            <i class="fas fa-plus"></i> Ajouter un Nouveau Log
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="stats-cards">
        <div class="stat-card"><div class="stat-number">{{ $logs->count() }}</div><div class="stat-label">Total des Logs</div></div>
        <div class="stat-card"><div class="stat-number">{{ $logs->unique('user_id')->count() }}</div><div class="stat-label">Utilisateurs Actifs</div></div>
        <div class="stat-card"><div class="stat-number">{{ $logs->unique('habit_id')->count() }}</div><div class="stat-label">Habits Suivis</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($logs->avg('value') ?? 0, 2) }}</div><div class="stat-label">Moyenne des Valeurs</div></div>
    </div>

    <div class="logs-table-container">
        @if($logs->count() > 0)
        <table class="logs-table">
            <thead>
                <tr><th>Activité</th><th>Utilisateur</th><th>Valeur</th><th>Date</th><th>QR</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                @php
                    $qrData = urlencode("🏋️‍♂️ Habitude: {$log->habit->title}\n📆 Date: {$log->logged_at}\n👤 Utilisateur: {$log->user->name}\nValeur: {$log->value}");
                @endphp
                <tr>
                    <td><div class="habit-badge"><i class="fas fa-running"></i>{{ $log->habit->title }}</div></td>
                    <td><div class="user-badge"><i class="fas fa-user"></i>{{ $log->user->name }}</div></td>
                    <td class="value-display">{{ $log->value }}</td>
                    <td class="date-display"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($log->logged_at)->format('d/m/Y H:i') }}</td>
                    <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $qrData }}" alt="QR" style="border-radius:8px;padding:4px;background:white;"></td>
                    <td>
                        <div class="actions-container">
                            <a href="{{ route('habit-logs.show', $log) }}" class="action-btn view-btn"><i class="fas fa-eye"></i>Voir</a>
                            <a href="{{ route('habit-logs.edit', $log) }}" class="action-btn edit-btn"><i class="fas fa-edit"></i>Modifier</a>
                            <form action="{{ route('habit-logs.destroy', $log) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" onclick="return confirm('Supprimer ce log ?')">
                                    <i class="fas fa-trash"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h3>Aucun log d'activité trouvé</h3>
            <p>Commencez à suivre vos progrès en ajoutant votre premier log !</p>
            <a href="{{ route('habit-logs.create') }}" class="add-log-btn" style="margin-top:20px;">
                <i class="fas fa-plus"></i> Ajouter le Premier Log
            </a>
        </div>
        @endif
    </div>

    <div class="pagination">
        @if($logs->onFirstPage())
            <span class="page-number" style="opacity:0.5;"><i class="fas fa-chevron-left"></i> Précédent</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="page-number"><i class="fas fa-chevron-left"></i> Précédent</a>
        @endif

        <span class="page-number active">{{ $logs->currentPage() }}</span>

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="page-number">Suivant <i class="fas fa-chevron-right"></i></a>
        @else
            <span class="page-number" style="opacity:0.5;">Suivant <i class="fas fa-chevron-right"></i></span>
        @endif
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-card').forEach((card, i) => card.style.animationDelay = `${i * 0.1}s`);
});
</script>
@endpush
