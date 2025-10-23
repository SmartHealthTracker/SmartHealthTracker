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
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .sport-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 40px 30px;
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
        top: 0; left: 0; right: 0; bottom: 0;
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

    .sport-dashboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 40px;
    }

    .left-column, .right-column {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .sport-card, .qr-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }

    .habit-main-header {
        display: flex;
        justify-content: flex-start;
        gap: 20px;
        align-items: flex-start;
        margin-bottom: 25px;
    }

    .habit-icon {
        font-size: 2.5rem;
        background: var(--sport-gradient);
        border-radius: 15px;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(0,176,255,0.3);
    }

    .habit-text h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .habit-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .meta-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 12px;
        border-left: 4px solid var(--primary);
        text-align: center;
    }

    .meta-label {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 5px;
    }

    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .sport-btn {
        padding: 12px 25px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.95rem;
        color: white;
    }

    .btn-primary { background: var(--sport-gradient); }
    .btn-secondary { background: var(--dark); }

    .sport-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .qr-code {
        width: 200px;
        height: 200px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        border: 3px solid white;
        margin: 0 auto 15px;
    }

    @media (max-width: 968px) { .sport-dashboard { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header -->
    <div class="sport-header fade-in-up">
        <h1 class="sport-title">📊 Détails du Log</h1>
        <p class="sport-subtitle">Informations complètes pour ce log sportif</p>
    </div>

    <div class="sport-dashboard">
        <!-- Left Column -->
        <div class="left-column">
            <div class="sport-card fade-in-up">
                <div class="habit-main-header">
                    <div class="habit-icon">🏋️</div>
                    <div class="habit-text">
                        <h2>Log #{{ $habitLog->id }}</h2>
                        <div class="habit-meta">
                            <div class="meta-item">
                                <div class="meta-label">Utilisateur</div>
                                <div class="meta-value">{{ $habitLog->user->name }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Habit</div>
                                <div class="meta-value">{{ $habitLog->habit->title }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Valeur</div>
                                <div class="meta-value">{{ $habitLog->value }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Date</div>
                                <div class="meta-value">{{ \Carbon\Carbon::parse($habitLog->logged_at)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons" style="margin-top:25px;">
                    <a href="{{ route('habit-logs.edit', $habitLog) }}" class="sport-btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier le Log
                    </a>

                    <a href="{{ route('habitssaif.show', $habitLog->habit) }}" class="sport-btn btn-primary">
                        <i class="fas fa-eye"></i> Voir Détails de l'Habit
                    </a>

                    <a href="{{ route('habit-logs.index') }}" class="sport-btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - QR Code -->
        <div class="right-column">
            <div class="qr-card fade-in-up">
                <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($habitLog->habit->title) }}" alt="QR Code Habitude">
                <p style="margin-top:15px; font-weight:600; color:var(--dark);">
                    📱 Scannez pour partager l’habitude
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
