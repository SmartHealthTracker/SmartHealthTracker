@extends('layout.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Suivi des Habits</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Habit</th>
                            <th>Progress</th>
                            <th>Durée</th>
                            <th>Action</th>
                            <th>Results</th>
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
                                    $elapsed = \Carbon\Carbon::parse($tracking->started_at)->diffInMinutes(now());
                                    $progress = min(100, round(($elapsed / $habit->duration) * 100));
                                    $startedAt = \Carbon\Carbon::parse($tracking->started_at)->timestamp;
                                } elseif ($habit->duration && $tracking->progress) {
                                    $progress = min(100, round(($tracking->progress / $habit->duration) * 100));
                                } else {
                                    $progress = $tracking->progress;
                                }
                            }
                        @endphp
                        <tr>
                            <td><img src="{{ $habit->icon }}" alt="icon" style="width:40px;height:40px;"></td>
                            <td>{{ $habit->name }}</td>
                            <td>
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
                                <span id="progress-label-{{ $habit->id }}">{{ $progress }}%</span>
                                <span id="timer-{{ $habit->id }}" class="badge badge-secondary ml-2"
                                    data-started="{{ $startedAt ?? '' }}"
                                    data-duration="{{ $habit->duration ?? '' }}"
                                    style="display:{{ ($state === 'in_progress' && $startedAt) ? '' : 'none' }}">
                                </span>
                            </td>
                            <td>{{ $habit->duration ?? '-' }} min</td>
                            <td>
                                @if($habit->type === 'nutrition')
                                    <button class="btn btn-outline-info done-btn" data-id="{{ $habit->id }}">Fait / Pas fait</button>
                                @else
                                    <button class="btn
                                        @if($progress >= 100 || $state === 'completed') btn-success
                                        @elseif($state === 'in_progress') btn-warning
                                        @else btn-outline-primary
                                        @endif
                                        start-btn"
                                        data-id="{{ $habit->id }}"
                                        data-duration="{{ $habit->duration }}"
                                        data-tracking="{{ $tracking->id ?? '' }}"
                                        @if($progress >= 100 || $state === 'completed') disabled @endif
                                    >
                                        @if($progress >= 100 || $state === 'completed') Completed
                                        @elseif($state === 'in_progress') In Progress
                                        @else Start
                                        @endif
                                    </button>
                                @endif
                            </td>
                            <td>
                                <span id="result-{{ $habit->id }}">
                                    @if($progress >= 100 || $state === 'completed') Done @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Nutrition OpenFoodFacts --}}
<div class="card mt-4">
    <div class="card-body">
        <h4 class="card-title">Calculateur Nutritionnel (OpenFoodFacts)</h4>
        <div class="form-group">
            <label for="ingredients">Entrez les ingrédients (séparés par des virgules) :</label>
            <input type="text" id="ingredients" class="form-control" placeholder="Ex: pomme, riz, poulet">
            <div id="ingredients-feedback" class="invalid-feedback" style="display:none;">
                Veuillez entrer au moins un ingrédient.
            </div>
        </div>
        <button id="check-nutrition" class="btn btn-primary mt-2">Vérifier Nutrition</button>

        <div class="mt-3" id="nutrition-result" style="display:none;">
            <h5>Résultats :</h5>
            <ul id="nutrition-list"></ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let intervals = {};
    let timers = {};

    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return (h > 0 ? (h < 10 ? '0' : '') + h + ':' : '') +
               (m < 10 ? '0' : '') + m + ':' +
               (s < 10 ? '0' : '') + s;
    }

    // Function to update backend progress
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

    // Function to complete habit in backend
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

    // Function to update UI when habit is completed
    function completeHabitUI(habitId, trackingId) {
        const button = document.querySelector(`.start-btn[data-id="${habitId}"]`);
        const progressBar = document.getElementById('progress-' + habitId);
        const progressLabel = document.getElementById('progress-label-' + habitId);
        const resultSpan = document.getElementById('result-' + habitId);
        const timerSpan = document.getElementById('timer-' + habitId);

        if (button) {
            button.textContent = "Completed";
            button.classList.remove("btn-warning", "btn-outline-primary");
            button.classList.add("btn-success");
            button.disabled = true;
        }

        if (progressBar) progressBar.style.width = '100%';
        if (progressLabel) progressLabel.textContent = '100%';
        if (resultSpan) resultSpan.textContent = 'Done';
        if (timerSpan) timerSpan.style.display = 'none';

        // Clear any running timers
        if (timers[habitId]) {
            clearInterval(timers[habitId]);
            delete timers[habitId];
        }
    }

    // Chronomètre auto pour les activités déjà en cours (après refresh)
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

                // Update backend progress every 10 seconds
                if (elapsed % 10 === 0) {
                    updateBackendProgress(trackingId, percent, habitId);
                }

                if (percent >= 100) {
                    if (timerSpan) timerSpan.textContent = formatTime(duration * 60);
                    if (timers[habitId]) clearInterval(timers[habitId]);

                    // Complete in backend and update UI
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
                button.textContent = "In Progress";
                button.classList.remove("btn-outline-primary");
                button.classList.add("btn-warning");

                // Récupérer le timestamp de départ pour le chronomètre
                    fetch(`/habit-trackings/${trackingId}`) 
                    .then(res => res.json())
                    .then(trackingData => {
                        let startedAt = trackingData.started_at
                            ? Math.floor(new Date(trackingData.started_at).getTime() / 1000)
                            : Math.floor(Date.now() / 1000);

                        if (timerSpan) {
                            timerSpan.style.display = '';
                            timerSpan.dataset.started = startedAt;
                        }

                        function updateTimer() {
                            const now = Math.floor(Date.now() / 1000);
                            const elapsed = now - startedAt;
                            const percent = Math.min(100, Math.round((elapsed / (duration * 60)) * 100));

                            if (progressBar) progressBar.style.width = percent + '%';
                            if (progressLabel) progressLabel.textContent = percent + '%';
                            if (timerSpan) timerSpan.textContent = formatTime(elapsed);

                            // Update backend progress every 10 seconds
                            if (elapsed % 10 === 0) {
                                updateBackendProgress(trackingId, percent, habitId);
                            }

                            if (percent >= 100) {
                                if (timerSpan) timerSpan.textContent = formatTime(duration * 60);
                                if (timers[habitId]) clearInterval(timers[habitId]);

                                // Complete in backend and update UI
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
                    if (resultSpan) resultSpan.textContent = 'Done';
                    button.textContent = "Completed";
                    button.classList.remove("btn-outline-info");
                    button.classList.add("btn-success");
                    Swal.fire('Bravo!', 'Habitude complétée!', 'success');
                });
            });
        });
    });

    // Nutrition button
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
                // Appel direct à l'API OpenFoodFacts (pas à la route Laravel)
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

    // Optionnel : retirer l'erreur dès que l'utilisateur saisit quelque chose
    document.getElementById('ingredients').addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
            document.getElementById('ingredients-feedback').style.display = 'none';
        }
    });

});
</script>
<style>
.badge-secondary {
    background-color: #6c757d;
    font-size: 0.9em;
}
</style>

@endsection
