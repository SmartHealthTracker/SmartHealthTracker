<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de plan d'entraînement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-dark p-5">

<div class="container">
    <h1 class="text-center text-warning mb-5">🏋️‍♂️ Générateur de plan d'entraînement personnalisé</h1>

    <!-- FORMULAIRE -->
    <div class="card bg-secondary p-4 mb-4 shadow-lg">
        <form action="{{ route('workout.generate') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="goal" class="form-label">🎯 Objectif :</label>
                <input type="text" id="goal" name="goal" value="Build muscle" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fitness_level" class="form-label">💪 Niveau :</label>
                <select id="fitness_level" name="fitness_level" class="form-select">
                    <option value="Beginner">Débutant</option>
                    <option value="Intermediate" selected>Intermédiaire</option>
                    <option value="Advanced">Avancé</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">⚙️ Préférences d'entraînement :</label>
                <div class="d-flex gap-2 flex-wrap">
                    <input type="text" name="preferences[]" value="Weight training" class="form-control w-50" placeholder="Ex : Weight training">
                    <input type="text" name="preferences[]" value="Cardio" class="form-control w-50" placeholder="Ex : Cardio">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">📅 Jours par semaine :</label>
                    <input type="number" name="days_per_week" value="4" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">⏱️ Durée par séance (min) :</label>
                    <input type="number" name="session_duration" value="60" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">📆 Durée du plan (semaines) :</label>
                    <input type="number" name="plan_duration_weeks" value="4" class="form-control">
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm">
                    🚀 Générer le plan
                </button>
            </div>
        </form>
    </div>

    <!-- AFFICHAGE DU PLAN SI DISPONIBLE -->
    @if(!empty($plan))
        <div class="card bg-dark border-warning p-4 mt-4">
            <h2 class="text-warning">📋 Plan d'entraînement généré</h2>
            <p><strong>🎯 Objectif :</strong> {{ $plan['goal'] ?? 'N/A' }}</p>

            @php
                $exercises = $plan['exercises'] ?? $plan['workouts'] ?? [];
            @endphp

            @if(is_array($exercises) && count($exercises) > 0)
                <ul class="list-group list-group-flush">
                    @foreach($exercises as $exercise)
                        <li class="list-group-item bg-secondary text-light">
                            <strong>{{ $exercise['name'] ?? 'N/A' }}</strong>
                            @if(!empty($exercise['sets']) && !empty($exercise['repetitions']))
                                : {{ $exercise['sets'] }} séries x {{ $exercise['repetitions'] }} répétitions
                            @endif
                            @if(!empty($exercise['duration']) && $exercise['duration'] != 'N/A')
                                - Durée : {{ $exercise['duration'] }}
                            @endif
                            @if(!empty($exercise['equipment']))
                                - Équipement : {{ $exercise['equipment'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mt-2">Aucun exercice disponible.</p>
            @endif
        </div>
    @endif
</div>

</body>
</html>
