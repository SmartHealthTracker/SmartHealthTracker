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
                <h5>Prédiction pour la semaine prochaine</h5>
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="mb-0 font-weight-medium">{{ $predictedCalories }}</h2>
                        <p class="mb-5 text-muted">Calories brûlées estimées</p>
                    </div>
                    <div class="col-md-8">
                        <h2 class="mb-0 font-weight-medium">{{ $trendAlert }}</h2>
                        <p class="mb-5 text-muted">Alerte tendance</p>
                    </div>
                </div>

                <!-- Graphique Ligne pour Prédictions -->
                <canvas id="predictionsChart" height="100"></canvas>
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