@extends('layout.master')
@section('title', 'Prédictions et Tendances')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="p-4 border-bottom bg-light">
                <h4 class="card-title mb-0">Prédictions Basées sur Vos Tendances</h4>
            </div>
            <div class="card-body">
                <h5>Prédictions pour les périodes futures</h5>
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ $predictedWeek }}</h2>
                        <p class="mb-5 text-muted">Semaine prochaine (calories estimées)</p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ $predictedMonth }}</h2>
                        <p class="mb-5 text-muted">Mois prochain (calories estimées)</p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ $predicted3Months }}</h2>
                        <p class="mb-5 text-muted">3 mois prochains (calories estimées)</p>
                    </div>
                </div>
                <p>Alerte tendance : {{ $trendAlert }}</p>

                <!-- Graphique Ligne pour Prédictions (évolution hebdomadaire sur 12 semaines) -->
                @if ($predictions->isNotEmpty())
                <h6>Évolution des Calories Prédites (hebdomadaire)</h6>
                <canvas id="predictionsChart" height="100"></canvas>
                @else
                <p class="text-center text-muted">Aucune prédiction disponible (ajoutez plus de logs d'activité pour améliorer les estimations).</p>
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
    @if ($predictions->isNotEmpty())
    const predCtx = document.getElementById('predictionsChart').getContext('2d');
    const predictionsChart = new Chart(predCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabelsPred) !!},
            datasets: [{
                label: 'Calories Prédites',
                data: {!! json_encode($chartDataPred) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: true
            }]
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
{{-- SweetAlert2 pour alertes --}}
@if ($trendAlert && str_contains($trendAlert, 'Baisse'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  Swal.fire({
    icon: 'warning',
    title: 'Alerte',
    text: '{{ $trendAlert }}',
    timer: 3000,
    showConfirmButton: false
  });
</script>
@endif
@endpush