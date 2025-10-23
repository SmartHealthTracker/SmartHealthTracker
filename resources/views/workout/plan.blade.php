<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plan d'entraînement généré</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark p-5">

<div class="container">
    <h2 class="mb-4 text-center text-warning">🏋️‍♂️ Plan d'entraînement généré</h2>

    <div class="card bg-secondary p-4 mb-4">
        <p><strong>🎯 Objectif :</strong> {{ $plan['goal'] ?? 'N/A' }}</p>
        <p><strong>💪 Niveau :</strong> {{ $plan['fitness_level'] ?? 'N/A' }}</p>
        <p><strong>📆 Durée totale :</strong> {{ $plan['total_weeks'] ?? 'N/A' }} semaines</p>
        <p><strong>🕓 Fréquence :</strong> {{ $plan['schedule']['days_per_week'] ?? 'N/A' }} jours/semaine</p>
        <p><strong>⏱️ Durée par séance :</strong> {{ $plan['schedule']['session_duration'] ?? 'N/A' }} minutes</p>
    </div>

    @if(isset($plan['exercises']) && is_array($plan['exercises']) && count($plan['exercises']) > 0)
        @foreach($plan['exercises'] as $dayPlan)
            <div class="card bg-dark border-warning mb-3 p-3">
                <h4 class="text-warning">📅 {{ $dayPlan['day'] ?? 'Jour inconnu' }}</h4>

                @if(isset($dayPlan['exercises']) && is_array($dayPlan['exercises']) && count($dayPlan['exercises']) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($dayPlan['exercises'] as $exercise)
                            <li class="list-group-item bg-secondary text-light">
                                <strong>{{ $exercise['name'] ?? 'N/A' }}</strong>
                                @if(isset($exercise['sets']) && isset($exercise['repetitions']))
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
                    <p>Aucun exercice pour ce jour.</p>
                @endif
            </div>
        @endforeach
    @else
        <p class="text-muted">Aucun exercice disponible.</p>
    @endif

    <hr>

    @if(isset($plan['seo_title']))
        <div class="card bg-secondary p-3 mt-4">
            <h4 class="text-info">🔍 Informations SEO</h4>
            <p><strong>Titre :</strong> {{ $plan['seo_title'] }}</p>
            <p><strong>Description :</strong> {{ $plan['seo_content'] }}</p>
            <p><strong>Mots-clés :</strong> {{ $plan['seo_keywords'] }}</p>
        </div>
    @endif
</div>

</body>
</html>
