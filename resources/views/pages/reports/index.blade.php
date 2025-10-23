@extends('layout.master')
@section('title', 'Rapports Personnalisés')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="p-4 border-bottom bg-light">
                <h4 class="card-title mb-0">Rapports d'Activités</h4>
            </div>
            <div class="card-body">
                <!-- Formulaire de filtres -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="period">Période</label>
                            <select name="period" id="period" class="form-control" onchange="this.form.submit()">
                                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personnalisée</option>
                            </select>
                        </div>
                        @if($period == 'custom')
                        <div class="col-md-4">
                            <label for="start_date">Date de début</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_date">Date de fin</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}" required>
                        </div>
                        @endif
                        <div class="col-md-12 mt-2">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </div>
                </form>

                <!-- Résumé -->
                <h5>Résumé pour la période : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h5>
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ $totalCalories }}</h2>
                        <p class="mb-5 text-muted">Total calories brûlées</p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ round($totalHours, 2) }} h</h2>
                        <p class="mb-5 text-muted">Total heures d'activité</p>
                    </div>
                </div>

                <!-- Graphique Mixed : Barres pour Calories et Ligne pour Heures -->
                @if ($repartition->isNotEmpty())
                <canvas id="combinedChart" height="100"></canvas>
                @else
                <p class="text-center text-muted">Aucune répartition disponible (pas d'activités enregistrées pour cette période).</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('custom-scripts')
<script>
    @if ($repartition->isNotEmpty())
    const ctx = document.getElementById('combinedChart').getContext('2d');
    const combinedChart = new Chart(ctx, {
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    type: 'bar',
                    label: 'Calories Brûlées',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    type: 'line',
                    label: 'Heures',
                    data: {!! json_encode($chartDataHours) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1,
                    fill: false
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
</script>
{{-- Message succès --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false
  });
</script>
@endif
@endpush