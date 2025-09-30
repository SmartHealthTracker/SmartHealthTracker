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
                            if ($tracking) {
                                $state = $tracking->state;
                                if ($state === 'in_progress' && $habit->duration && $tracking->started_at) {
                                    $elapsed = \Carbon\Carbon::parse($tracking->started_at)->diffInMinutes(now());
                                    $progress = min(100, round(($elapsed / $habit->duration) * 100));
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

    function updateProgressBar(habitId, trackingId, duration, button, progressBar, progressLabel, resultSpan) {
        fetch(`/habit-trackings/${trackingId}/update`, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            let percent = Math.min(100, Math.round(data.progress));
            progressBar.style.width = percent + '%';
            progressLabel.textContent = percent + '%';
            if (data.state === 'completed') {
                clearInterval(intervals[habitId]);
                resultSpan.textContent = 'Done';
                button.textContent = "Completed";
                button.classList.remove("btn-warning");
                button.classList.add("btn-success");
                Swal.fire('Bravo!', 'Habitude complétée!', 'success');
            }
        });
    }

    // Habits buttons
    document.querySelectorAll('.start-btn').forEach(button => {
        button.addEventListener('click', () => {
            const habitId = button.dataset.id;
            const duration = parseInt(button.dataset.duration);
            const progressBar = document.getElementById('progress-' + habitId);
            const progressLabel = document.getElementById('progress-label-' + habitId);
            const resultSpan = document.getElementById('result-' + habitId);

            if (intervals[habitId]) clearInterval(intervals[habitId]);
            if (!duration || duration <= 0) {
                Swal.fire('Erreur', 'Durée non définie pour cette habitude.', 'error');
                return;
            }

            fetch(`/habits/${habitId}/start`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                const trackingId = data.tracking_id;
                button.dataset.tracking = trackingId;
                button.textContent = "In Progress";
                button.classList.remove("btn-outline-primary");
                button.classList.add("btn-warning");

                updateProgressBar(habitId, trackingId, duration, button, progressBar, progressLabel, resultSpan);

                intervals[habitId] = setInterval(() => {
                    updateProgressBar(habitId, trackingId, duration, button, progressBar, progressLabel, resultSpan);
                }, 60000);
            });
        });
    });

    document.querySelectorAll('.done-btn').forEach(button => {
        button.addEventListener('click', () => {
            const habitId = button.dataset.id;
            fetch(`/habits/${habitId}/start`, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
            .then(res => res.json())
            .then(data => {
                const trackingId = data.tracking_id;
                fetch(`/habit-trackings/${trackingId}/finish`, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
                .then(() => {
                    const progressBar = document.getElementById('progress-' + habitId);
                    const progressLabel = document.getElementById('progress-label-' + habitId);
                    const resultSpan = document.getElementById('result-' + habitId);
                    progressBar.style.width = '100%';
                    progressLabel.textContent = '100%';
                    resultSpan.textContent = 'Done';
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
        const ingredientsInput = document.getElementById('ingredients').value.trim();
        const resultDiv = document.getElementById('nutrition-result');
        const nutritionList = document.getElementById('nutrition-list');

        if (!ingredientsInput) {
            Swal.fire('Erreur', 'Veuillez entrer au moins un ingrédient.', 'error');
            return;
        }

        const ingredients = ingredientsInput.split(',').map(i => i.trim());
        nutritionList.innerHTML = '';
        resultDiv.style.display = 'block';

        for (let ingredient of ingredients) {
            try {
                const response = await fetch("{{ route('nutrition.get') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ingredient })
                });
                const data = await response.json();

                if (data.error) {
                    nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - ${data.error}</li>`;
                } else {
                    nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - Calories: ${data.calories} kcal, Protéines: ${data.protein} g, Glucides: ${data.carbs} g, Lipides: ${data.fat} g</li>`;
                }
            } catch (error) {
                nutritionList.innerHTML += `<li><strong>${ingredient}</strong> - Erreur serveur</li>`;
            }
        }
    });

});
</script>
@endsection
