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

    /* Main Content Grid */
    .sport-dashboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 40px;
    }

    /* Sport Card */
    .sport-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }

    .sport-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--sport-gradient);
    }

    .habit-main-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .habit-title-section {
        display: flex;
        align-items: center;
        gap: 15px;
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

    .habit-text h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .habit-category {
        background: var(--accent);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255,64,129,0.3);
    }

    /* Habit Details */
    .habit-details {
        margin: 25px 0;
    }

    .habit-description {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #555;
        margin-bottom: 20px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 15px;
        border-left: 4px solid var(--primary);
    }

    .habit-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .meta-item {
        background: white;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        text-align: center;
        transition: all 0.3s ease;
    }

    .meta-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .meta-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark);
    }

    /* Progress Section */
    .progress-section {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin: 25px 0;
        box-shadow: 0 8px 25px rgba(102,126,234,0.3);
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .progress-bar {
        width: 100%;
        height: 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--secondary), #69f0ae);
        border-radius: 10px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmer 2s infinite;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 30px 0;
    }

    .stat-card {
        background: white;
        padding: 25px 20px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--sport-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* AI Advice */
    .ai-advice-section {
        background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
        padding: 25px;
        border-radius: 15px;
        margin: 25px 0;
        border-left: 4px solid var(--accent);
        position: relative;
    }

    .ai-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .ai-icon {
        font-size: 1.5rem;
        background: var(--accent);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-header h3 {
        font-size: 1.3rem;
        color: var(--dark);
        font-weight: 700;
    }

    .ai-content {
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
        font-style: italic;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin: 25px 0;
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
    }

    .btn-primary {
        background: var(--sport-gradient);
        color: white;
        box-shadow: 0 6px 20px rgba(0,176,255,0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ff5252, #ff4081);
        color: white;
        box-shadow: 0 6px 20px rgba(255,82,82,0.3);
    }

    .sport-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    /* QR Section */
    .qr-section {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .qr-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .qr-code {
        width: 200px;
        height: 200px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        border: 3px solid white;
        margin: 0 auto 15px;
    }

    .tip-card {
        background: linear-gradient(135deg, #fff3e0, #ffecb3);
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        color: #e65100;
        font-weight: 500;
        box-shadow: 0 6px 20px rgba(255,152,0,0.2);
    }

    /* Back Button */
    .back-container {
        text-align: center;
        margin-top: 40px;
    }

    .back-btn {
        background: linear-gradient(135deg, var(--dark), #303f9f);
        color: white;
        padding: 14px 35px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(26,35,126,0.3);
    }

    .back-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(26,35,126,0.4);
        color: white;
    }

    /* Animations */
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .sport-dashboard {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .sport-title {
            font-size: 2rem;
        }
        
        .habit-text h1 {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 640px) {
        .container {
            padding: 15px;
        }
        
        .sport-header {
            padding: 30px 20px;
        }
        
        .habit-main-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .habit-title-section {
            flex-direction: column;
            text-align: center;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .sport-btn {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Sport Header -->
    <div class="sport-header fade-in-up">
        <h1 class="sport-title">🏆 DÉTAILS DE L'HABITUDE SPORTIVE</h1>
        <p class="sport-subtitle">Suivez votre progression et optimisez vos performances</p>
    </div>

    <!-- Main Dashboard -->
    <div class="sport-dashboard">
        <!-- Left Column - Main Content -->
        <div class="left-column">
            <!-- Main Habit Card -->
            <div class="sport-card fade-in-up">
                <div class="habit-main-header">
                    <div class="habit-title-section">
                        <div class="habit-icon">
                            {{ $habit->category_icon ?? '💪' }}
                        </div>
                        <div class="habit-text">
                            <h1>{{ $habit->title }}</h1>
                            <div class="habit-meta">
                                <div class="meta-item">
                                    <div class="meta-label">👤 Utilisateur</div>
                                    <div class="meta-value">{{ $habit->user->name ?? 'Athlète' }}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">📅 Créé le</div>
<div class="meta-value">
    {{ $habit->created_at ? $habit->created_at->format('d/m/Y') : 'Non défini' }}
</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($habit->category)
                        <span class="habit-category">{{ $habit->category }}</span>
                    @endif
                </div>

                <!-- Description -->
                @if($habit->description)
                    <div class="habit-description">
                        {{ $habit->description }}
                    </div>
                @endif

                <!-- Target Info -->
                @if($habit->target_value)
                    <div class="meta-item" style="grid-column: 1 / -1; text-align: center;">
                        <div class="meta-label">🎯 Objectif Principal</div>
                        <div class="meta-value" style="font-size: 1.3rem; color: var(--secondary);">
                            {{ $habit->target_value }} {{ $habit->unit ?? 'unités' }}
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
<div class="action-buttons">
    <!-- Edit Button -->
    <a href="{{ route('habitssaif.edit', $habit) }}" class="sport-btn btn-primary">
        <i class="fas fa-edit"></i> Modifier
    </a>

    <!-- Delete Button -->
    <form action="{{ route('habitssaif.destroy', $habit) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="sport-btn btn-danger"
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet objectif ?')">
            <i class="fas fa-trash"></i> Supprimer
        </button>
    </form>

    <!-- Download Report Button -->
    <a href="{{ route('habitssaif.downloadReport', $habit) }}" 
       class="sport-btn" 
       style="background: linear-gradient(135deg, #ff9800, #ff5722); color: white;">
       <i class="fas fa-download"></i> Télécharger le rapport IA
    </a>
</div>


                <!-- Progress Section -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span>Progression Globale</span>
                        <span id="progress-value">{{ $progress }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div id="progress-fill" class="progress-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    @foreach($stats as $stat)
                        <div class="stat-card">
                            <div class="stat-value">{{ $stat['value'] }}</div>
                            <div class="stat-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- AI Advice -->
                <div class="ai-advice-section">
                    <div class="ai-header">
                        <div class="ai-icon">🤖</div>
                        <h3>CONSEIL ENTRAÎNEUR IA</h3>
                    </div>
                    <div class="ai-content">
                        {{ $advice }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - QR & Tips -->
        <div class="right-column">
            <!-- QR Card -->
            <div class="qr-card fade-in-up">
                <img class="qr-code" src="{{ $qrCodeUrl }}" alt="QR Code Habitude Sportive">
                <p style="margin-top: 15px; font-weight: 600; color: var(--dark);">
                    📱 Scannez pour partager votre objectif
                </p>
            </div>

            <!-- Tip Card -->
            <div id="ai-advice" class="tip-card fade-in-up" style="display:none; margin-top:20px;">
    <div style="font-size: 1.5rem;">💡</div>
    <h4 style="color:#e65100;">ASTUCE IA PERSONNALISÉE</h4>
    <p id="ai-advice-text" style="font-size:0.95rem; line-height:1.5;">
        Chargement du conseil...
    </p>
</div>

        </div>
    </div>

    <!-- Back Button -->
    <div class="back-container">
        <a href="{{ route('habits.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
        </a>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation de la barre de progression
    const progressFill = document.getElementById('progress-fill');
    const progressValue = document.getElementById('progress-value');
    const progress = {{ $progress }};
    
    // Animation fluide
    let currentProgress = 0;
    const duration = 1800;
    const startTime = performance.now();
    
    function animateProgress(currentTime) {
        const elapsed = currentTime - startTime;
        const progressPercentage = Math.min(elapsed / duration, 1);
        
        // Easing function
        const easeOutQuart = 1 - Math.pow(1 - progressPercentage, 4);
        currentProgress = Math.floor(progress * easeOutQuart);
        
        progressFill.style.width = currentProgress + '%';
        progressValue.textContent = currentProgress + '%';
        
        if (progressPercentage < 1) {
            requestAnimationFrame(animateProgress);
        } else {
            progressValue.textContent = progress + '%';
        }
    }
    
    // Démarrer l'animation
    setTimeout(() => {
        requestAnimationFrame(animateProgress);
    }, 500);
    
    // Animation des cartes au survol
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Animation des boutons
    const buttons = document.querySelectorAll('.sport-btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation de la barre de progression
    const progressFill = document.getElementById('progress-fill');
    const progressValue = document.getElementById('progress-value');
    const progress = {{ $progress }};
    
    // Animation fluide
    let currentProgress = 0;
    const duration = 1800;
    const startTime = performance.now();
    
    function animateProgress(currentTime) {
        const elapsed = currentTime - startTime;
        const progressPercentage = Math.min(elapsed / duration, 1);
        
        const easeOutQuart = 1 - Math.pow(1 - progressPercentage, 4);
        currentProgress = Math.floor(progress * easeOutQuart);
        
        progressFill.style.width = currentProgress + '%';
        progressValue.textContent = currentProgress + '%';
        
        if (progressPercentage < 1) {
            requestAnimationFrame(animateProgress);
        } else {
            progressValue.textContent = progress + '%';
        }
    }
    
    // Démarrer l'animation
    setTimeout(() => {
        requestAnimationFrame(animateProgress);
    }, 500);
    
    // Charger le conseil Gemini
    loadGeminiAdvice();
    
    function loadGeminiAdvice() {
        const adviceElement = document.getElementById('ai-advice-text');
        const adviceContainer = document.getElementById('ai-advice');
        
        if (!adviceElement || !adviceContainer) {
            console.error('Éléments Gemini non trouvés');
            return;
        }
        
        adviceElement.textContent = '🔄 Chargement du conseil personnalisé...';
        
        fetch(`/api/gemini-advice/{{ $habit->id }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.message);
                }
                
                adviceElement.textContent = data.advice;
                adviceContainer.style.display = 'block';
                
                // Animation d'apparition
                adviceContainer.style.opacity = '0';
                adviceContainer.style.transform = 'translateY(20px)';
                adviceContainer.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    adviceContainer.style.opacity = '1';
                    adviceContainer.style.transform = 'translateY(0)';
                }, 100);
            })
            .catch(error => {
                console.error('Erreur Gemini:', error);
                adviceElement.textContent = '💡 Conseil du jour : La persévérance est la clé de tous les succès !';
                adviceContainer.style.display = 'block';
            });
    }
    
    // Animation des cartes au survol
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Animation des boutons
    const buttons = document.querySelectorAll('.sport-btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endpush

@endpush