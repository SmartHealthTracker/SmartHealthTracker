@extends('layout.master')

@section('content')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $today = Carbon::today()->toDateString();
    $totalHabits = $habits->count();
    $completedToday = 0;
    $inProgressCount = 0;

    foreach ($habits as $habitSummary) {
        $latestSummaryTracking = $habitSummary->trackings->sortByDesc('date')->first();
        if ($latestSummaryTracking) {
            if ($latestSummaryTracking->date === $today && $latestSummaryTracking->state === 'completed') {
                $completedToday++;
            }
            if ($latestSummaryTracking->state === 'in_progress') {
                $inProgressCount++;
            }
        }
    }
@endphp

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card habit-board">
        <div class="card-body">
            <div class="habit-header d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title mb-1">Tableau des habitudes</h4>
                    <span class="text-muted">Suivez vos routines quotidiennes, vos progrès et vos actions à venir.</span>
                </div>
                <div class="habit-summary d-flex flex-wrap">
                    <div class="habit-summary-pill">
                        <span class="label">Habitudes</span>
                        <strong>{{ $totalHabits }}</strong>
                    </div>
                    <div class="habit-summary-pill">
                        <span class="label">Complétées aujourd'hui</span>
                        <strong>{{ $completedToday }}</strong>
                    </div>
                    <div class="habit-summary-pill">
                        <span class="label">En cours</span>
                        <strong>{{ $inProgressCount }}</strong>
                    </div>
                </div>
            </div>

            @if($habits->isEmpty())
                <div class="habit-empty-state text-center py-5">
                    <div class="habit-empty-icon mb-3">
                        <i class="mdi mdi-calendar-plus"></i>
                    </div>
                    <h5 class="mb-2">Aucune habitude enregistrée</h5>
                    <p class="text-muted mb-4">Ajoutez votre première habitude pour commencer à mesurer vos progrès quotidiens.</p>
                    <a href="{{ route('habits.create') }}" class="btn btn-gradient">Ajouter une habitude</a>
                </div>
            @else
                <div class="table-responsive habit-table-wrapper">
                    <table class="table habit-table align-middle">
                        <thead>
                            <tr>
                                <th>Habitude</th>
                                <th>Progression</th>
                                <th>Durée</th>
                                <th>Actions</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($habits as $habit)
                            @php
                                $tracking = $habit->trackings->sortByDesc('date')->first();
                                $progress = 0;
                                $state = 'not_started';
                                $startedAt = null;
                                if ($tracking) {
                                    $state = $tracking->state;
                                    if ($state === 'in_progress' && $habit->duration && $tracking->started_at) {
                                        $elapsed = Carbon::parse($tracking->started_at)->diffInMinutes(now());
                                        $progress = min(100, round(($elapsed / $habit->duration) * 100));
                                        $startedAt = Carbon::parse($tracking->started_at)->timestamp;
                                    } elseif ($habit->duration && $tracking->progress) {
                                        $progress = min(100, round(($tracking->progress / $habit->duration) * 100));
                                    } else {
                                        $progress = $tracking->progress;
                                    }
                                }

                                $iconMarkup = '<span class="habit-icon-square"><i class="mdi mdi-heart-pulse"></i></span>';
                                if ($habit->icon) {
                                    if (Str::contains($habit->icon, 'mdi')) {
                                        $iconMarkup = '<span class="habit-icon-square"><i class="'.$habit->icon.'"></i></span>';
                                    } elseif (Str::startsWith($habit->icon, ['http://', 'https://', '/'])) {
                                        $iconMarkup = '<span class="habit-icon-square"><img src="'.$habit->icon.'" alt="icon"></span>';
                                    }
                                }
                            @endphp
                            <tr class="habit-row">
                                <td>
                                    <div class="d-flex align-items-center">
                                        {!! $iconMarkup !!}
                                        <div class="ml-3">
                                            <span class="habit-name">{{ $habit->name }}</span>
                                            <span class="badge habit-type-badge habit-type-{{ $habit->type }}">{{ ucfirst($habit->type) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="habit-progress">
                                        <div class="progress">
                                            <div class="progress-bar
                                                @if($habit->type === 'nutrition') bg-info
                                                @elseif($habit->type === 'sport') bg-danger
                                                @elseif($habit->type === 'sleep') bg-primary
                                                @elseif($habit->type === 'study') bg-warning
                                                @elseif($habit->type === 'reading') bg-success
                                                @else bg-secondary
                                                @endif"
                                                role="progressbar"
                                                id="progress-{{ $habit->id }}"
                                                style="width: {{ $progress }}%;"
                                                aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="habit-progress-meta">
                                            <span id="progress-label-{{ $habit->id }}">{{ $progress }}%</span>
                                            <span id="timer-{{ $habit->id }}" class="badge badge-secondary ml-2"
                                                data-started="{{ $startedAt ?? '' }}"
                                                data-duration="{{ $habit->duration ?? '' }}"
                                                style="display:{{ ($state === 'in_progress' && $startedAt) ? '' : 'none' }}">
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        @if($habit->duration)
                                            {{ $habit->duration }} min
                                        @else
                                            —
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($habit->type === 'nutrition')
                                        <button class="btn btn-outline-info btn-sm rounded-pill done-btn" data-id="{{ $habit->id }}">Fait / Pas fait</button>
                                    @else
                                        <button class="btn btn-sm rounded-pill start-btn
                                            @if($progress >= 100 || $state === 'completed') btn-success
                                            @elseif($state === 'in_progress') btn-warning text-dark
                                            @else btn-outline-primary
                                            @endif"
                                            data-id="{{ $habit->id }}"
                                            data-duration="{{ $habit->duration }}"
                                            data-tracking="{{ $tracking->id ?? '' }}"
                                            @if($progress >= 100 || $state === 'completed') disabled @endif>
                                            @if($progress >= 100 || $state === 'completed') Terminé
                                            @elseif($state === 'in_progress') En cours
                                            @else Démarrer
                                            @endif
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <span id="result-{{ $habit->id }}" class="habit-status @if($progress >= 100 || $state === 'completed') habit-status-done @elseif($state === 'in_progress') habit-status-progress @endif">
                                        @if($progress >= 100 || $state === 'completed') Terminé
                                        @elseif($state === 'in_progress') En cours
                                        @else —
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="card nutrition-card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="card-title mb-1">Calculateur Nutritionnel</h4>
                <p class="card-description mb-0 text-muted">Analysez rapidement vos ingrédients grâce à OpenFoodFacts.</p>
            </div>
        </div>
        <div class="form-group">
            <label for="ingredients">Entrez les ingrédients (séparés par des virgules)</label>
            <input type="text" id="ingredients" class="form-control" placeholder="Ex : pomme, riz, poulet">
            <div id="ingredients-feedback" class="invalid-feedback" style="display:none;">
                Veuillez entrer au moins un ingrédient.
            </div>
        </div>
        <button id="check-nutrition" class="btn btn-gradient mt-2">Vérifier la nutrition</button>

        <div class="mt-3" id="nutrition-result" style="display:none;">
            <h5>Résultats :</h5>
            <ul id="nutrition-list"></ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.habit-board { border: none; border-radius: 18px; }
.habit-header .card-title { font-size: 1.4rem; }
.habit-summary { gap: 12px; }
.habit-summary-pill { background: #f5f7ff; border-radius: 12px; padding: 10px 18px; text-align: center; min-width: 140px; }
.habit-summary-pill .label { display:block; font-size: 0.75rem; text-transform: uppercase; color: #6c7a91; letter-spacing: 0.05em; }
.habit-summary-pill strong { font-size: 1.3rem; color: #1f3c88; }
.habit-empty-state { background: #f9faff; border-radius: 18px; }
.habit-empty-icon { font-size: 3rem; color: #4f8af3; }
.habit-table-wrapper { border-radius: 12px; overflow: hidden; }
.habit-table thead th { border-bottom: none; font-weight: 600; color: #6c7a91; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.06em; }
.habit-table tbody tr { border-bottom: 1px solid #eef1f7; }
.habit-table tbody tr:last-child { border-bottom: none; }
.habit-icon-square { width: 48px; height: 48px; border-radius: 14px; background: #eef4ff; display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:#4f8af3; box-shadow:0 6px 12px rgba(79,138,243,0.15); }
.habit-icon-square img { max-width:60%; max-height:60%; }
.habit-name { font-weight: 600; font-size: 1rem; }
.habit-type-badge { font-size: 0.7rem; border-radius: 999px; padding: 4px 10px; text-transform: capitalize; }
.habit-type-nutrition { background: rgba(23,162,184,0.15); color: #138496; }
.habit-type-sport { background: rgba(255,95,109,0.15); color: #e63950; }
.habit-type-sleep { background: rgba(94,114,228,0.15); color: #5e72e4; }
.habit-type-study { background: rgba(255,181,71,0.18); color: #c58a00; }
.habit-type-reading { background: rgba(72,199,142,0.18); color: #2f855a; }
.habit-progress .progress { height: 10px; border-radius: 999px; background: #edf2ff; }
.habit-progress-meta { font-size: 0.85rem; color: #6c7a91; margin-top: 6px; display:flex; align-items:center; }
.start-btn, .done-btn { min-width: 120px; }
.habit-status { font-weight: 600; font-size: 0.85rem; padding: 6px 12px; border-radius: 999px; background: #f1f3f9; display:inline-block; }
.habit-status-done { background: rgba(72,199,142,0.15); color: #2f855a; }
.habit-status-progress { background: rgba(94,114,228,0.15); color: #4c51bf; }
.nutrition-card { border: none; border-radius: 18px; }
.btn-gradient { background:linear-gradient(135deg,#4f8af3,#6ad4c5); color:#fff; border:none; }
.btn-gradient:hover { color:#fff; box-shadow:0 10px 18px rgba(79,138,243,0.25); }
.badge-secondary { background-color: #6c757d; font-size: 0.85rem; }
@media(max-width: 992px){
    .habit-summary { width:100%; }
    .habit-summary-pill { flex:1 1 30%; }
}
</style>
@endpush

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timers = {};
    const intervals = {};

    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return (h > 0 ? (h < 10 ? '0' : '') + h + ':' : '') +
               (m < 10 ? '0' : '') + m + ':' +
               (s < 10 ? '0' : '') + s;
    }

    function updateBackendProgress(trackingId, progress, habitId) {
        return fetch(`/habit-trackings/${trackingId}/update-progress`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ progress: progress })
        })
        .then(res => res.json())
        .then(data => {
            console.log(`Progress updated to ${progress}% for habit ${habitId}`);
            return data;
        })
        .catch(err => {
            console.error('Error updating progress:', err);
        });
    }

    function completeHabitBackend(trackingId, habitId) {
        return fetch(`/habit-trackings/${trackingId}/finish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            console.log(`Habit ${habitId} completed in backend`);
            return data;
        })
        .catch(err => {
            console.error('Error completing habit:', err);
        });
    }

    function completeHabitUI(habitId, trackingId) {
        const button = document.querySelector(`.start-btn[data-id="${habitId}"]`);
        const progressBar = document.getElementById('progress-' + habitId);
        const progressLabel = document.getElementById('progress-label-' + habitId);
        const resultSpan = document.getElementById('result-' + habitId);
        const timerSpan = document.getElementById('timer-' + habitId);

        if (button) {
            button.textContent = "Terminé";
            button.classList.remove("btn-warning", "btn-outline-primary", "text-dark");
            button.classList.add("btn-success");
            button.disabled = true;
        }

        if (progressBar) progressBar.style.width = '100%';
        if (progressLabel) progressLabel.textContent = '100%';
        if (resultSpan) {
            resultSpan.textContent = 'Terminé';
            resultSpan.classList.add('habit-status-done');
        }
        if (timerSpan) timerSpan.style.display = 'none';

        if (timers[habitId]) {
            clearInterval(timers[habitId]);
            delete timers[habitId];
        }
    }

    document.querySelectorAll('[id^="timer-"]').forEach(function(timerSpan) {
        const habitId = timerSpan.id.replace('timer-', '');
        const startedAt = parseInt(timerSpan.dataset.started);
        const duration = parseInt(timerSpan.dataset.duration);
        const trackingId = document.querySelector(`.start-btn[data-id="${habitId}"]`)?.dataset.tracking;

        if (startedAt && duration && trackingId) {
            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                const elapsed = now - startedAt;
                const percent = Math.min(100, Math.round((elapsed / (duration * 60)) * 100));

                const progressBar = document.getElementById('progress-' + habitId);
                const progressLabel = document.getElementById('progress-label-' + habitId);

                if (progressBar) progressBar.style.width = percent + '%';
                if (progressLabel) progressLabel.textContent = percent + '%';
                if (timerSpan) timerSpan.textContent = formatTime(elapsed);

                if (elapsed % 10 === 0) {
                    updateBackendProgress(trackingId, percent, habitId);
                }

                if (percent >= 100) {
                    if (timerSpan) timerSpan.textContent = formatTime(duration * 60);
                    if (timers[habitId]) clearInterval(timers[habitId]);

                    completeHabitBackend(trackingId, habitId)
                        .then(() => {
                            completeHabitUI(habitId, trackingId);
                        });
                }
            }

            updateTimer();
            timers[habitId] = setInterval(updateTimer, 1000);
            if (timerSpan) timerSpan.style.display = '';
        }
    });

    document.querySelectorAll('.start-btn').forEach(button => {
        button.addEventListener('click', () => {
            const habitId = button.dataset.id;
            const duration = parseInt(button.dataset.duration);
            const progressBar = document.getElementById('progress-' + habitId);
            const progressLabel = document.getElementById('progress-label-' + habitId);
            const resultSpan = document.getElementById('result-' + habitId);
            const timerSpan = document.getElementById('timer-' + habitId);

            if (intervals[habitId]) clearInterval(intervals[habitId]);
            if (timers[habitId]) clearInterval(timers[habitId]);

            if (!duration || duration <= 0) {
                Swal.fire('Erreur', 'Durée non définie pour cette habitude.', 'error');
                return;
            }

            fetch(`/habits/${habitId}/start`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                const trackingId = data.tracking_id;
                button.dataset.tracking = trackingId;
                button.textContent = "En cours";
                button.classList.remove("btn-outline-primary");
                button.classList.add("btn-warning", "text-dark");

                fetch(`/habit-trackings/${trackingId}`)
                    .then(res => res.json())
                    .then(trackingData => {
                        let startedAt = trackingData.started_at
                            ? Math.floor(new Date(trackingData.started_at).getTime() / 1000)
                            : Math.floor(Date.now() / 1000);

                        function updateTimer() {
                            const now = Math.floor(Date.now() / 1000);
                            const elapsed = now - startedAt;
                            const percent = Math.min(100, Math.round((elapsed / (duration * 60)) * 100));

                            if (progressBar) progressBar.style.width = percent + '%';
                            if (progressLabel) progressLabel.textContent = percent + '%';
                            if (timerSpan) {
                                timerSpan.style.display = '';
                                timerSpan.textContent = formatTime(elapsed);
                            }

                            if (elapsed % 10 === 0) {
                                updateBackendProgress(trackingId, percent, habitId);
                            }

                            if (percent >= 100) {
                                if (timerSpan) timerSpan.textContent = formatTime(duration * 60);
                                if (timers[habitId]) {
                                    clearInterval(timers[habitId]);
                                    delete timers[habitId];
                                }

                                completeHabitBackend(trackingId, habitId)
                                    .then(() => {
                                        completeHabitUI(habitId, trackingId);
                                        Swal.fire('Bravo!', 'Habitude complétée avec succès!', 'success');
                                    });
                            }
                        }

                        updateTimer();
                        timers[habitId] = setInterval(updateTimer, 1000);
                    });
            })
            .catch(err => {
                console.error('Error starting habit:', err);
                Swal.fire('Erreur', 'Impossible de démarrer l\'habitude.', 'error');
            });
        });
    });

    document.querySelectorAll('.done-btn').forEach(button => {
        button.addEventListener('click', () => {
            const habitId = button.dataset.id;
            fetch(`/habits/${habitId}/start`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
            .then(res => res.json())
            .then(data => {
                const trackingId = data.tracking_id;
                fetch(`/habit-trackings/${trackingId}/finish`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                })
                .then(() => {
                    const progressBar = document.getElementById('progress-' + habitId);
                    const progressLabel = document.getElementById('progress-label-' + habitId);
                    const resultSpan = document.getElementById('result-' + habitId);
                    if (progressBar) progressBar.style.width = '100%';
                    if (progressLabel) progressLabel.textContent = '100%';
                    if (resultSpan) {
                        resultSpan.textContent = 'Terminé';
                        resultSpan.classList.add('habit-status-done');
                    }
                    button.textContent = "Terminé";
                    button.classList.remove("btn-outline-info");
                    button.classList.add("btn-success");
                    Swal.fire('Bravo!', 'Habitude complétée!', 'success');
                });
            });
        });
    });

    document.getElementById('check-nutrition').addEventListener('click', async function() {
        const ingredientsInput = document.getElementById('ingredients');
        const resultDiv = document.getElementById('nutrition-result');
        const nutritionList = document.getElementById('nutrition-list');
        const feedback = document.getElementById('ingredients-feedback');

        if (!ingredientsInput.value.trim()) {
            ingredientsInput.classList.add('is-invalid');
            feedback.style.display = 'block';
            ingredientsInput.focus();
            return;
        } else {
            ingredientsInput.classList.remove('is-invalid');
            feedback.style.display = 'none';
        }

        const ingredients = ingredientsInput.value.trim().split(',').map(i => i.trim());
        nutritionList.innerHTML = '';
        resultDiv.style.display = 'block';

        for (let ingredient of ingredients) {
            try {
                const response = await fetch(`https://world.openfoodfacts.org/cgi/search.pl?search_terms=${encodeURIComponent(ingredient)}&search_simple=1&action=process&json=1`);
                const data = await response.json();

                if (data.products && data.products.length > 0) {
                    const product = data.products[0];
                    const calories = product.nutriments['energy-kcal_100g'] ?? 'N/A';
                    const protein = product.nutriments['proteins_100g'] ?? 'N/A';
                    const carbs = product.nutriments['carbohydrates_100g'] ?? 'N/A';
                    const fat = product.nutriments['fat_100g'] ?? 'N/A';

                    nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - Calories: ${calories} kcal, Protéines: ${protein} g, Glucides: ${carbs} g, Lipides: ${fat} g</li>`;
                } else {
                    nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - Aucun résultat trouvé</li>`;
                }
            } catch (error) {
                nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - Erreur serveur</li>`;
            }
        }
    });

    document.getElementById('ingredients').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            document.getElementById('ingredients-feedback').style.display = 'none';
        }
    });

});
</script>
@endpush
