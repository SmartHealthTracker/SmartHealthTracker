@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
<div class="row">

  {{-- Formulaire pour ajouter un HealthLog --}}
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Ajouter un log santé</h4>
        <form action="{{ route('health.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="weight">Poids (kg)</label>
              <input type="number" step="0.1" name="weight" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="height">Taille (cm)</label>
              <input type="number" name="height" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="water">Eau (ml)</label>
              <input type="number" name="water" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="steps">Nombre de pas</label>
              <input type="number" name="steps" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="food_name">Aliment</label>
              <input type="text" name="food_name" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="calories">Calories</label>
              <input type="number" name="calories" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="protein">Protéines (g)</label>
              <input type="number" name="protein" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="carbs">Glucides (g)</label>
              <input type="number" name="carbs" class="form-control">
            </div>
            <div class="form-group col-md-2">
              <label for="fat">Lipides (g)</label>
              <input type="number" name="fat" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="sleep_hours">Heures de sommeil</label>
              <input type="number" step="0.1" name="sleep_hours" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="heart_rate">Fréquence cardiaque</label>
              <input type="number" name="heart_rate" class="form-control">
            </div>
            <div class="form-group col-md-3">
              <label for="blood_pressure">Tension (ex: 120/80)</label>
              <input type="text" name="blood_pressure" class="form-control">
            </div>
          </div>

          <button type="submit" class="btn btn-success mt-2">Ajouter</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Tableau des logs --}}
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Historique Santé</h4>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Eau (ml)</th>
                <th>Poids</th>
                <th>Taille</th>
                <th>Pas</th>
                <th>Aliment</th>
                <th>Calories</th>
                <th>Prot</th>
                <th>Carbs</th>
                <th>Fat</th>
                <th>Sommeil</th>
                <th>HR</th>
                <th>Tension</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($logs as $log)
              <tr>
                <td>{{ $log->date }}</td>
                <td>{{ $log->water }}</td>
                <td>{{ $log->weight }}</td>
                <td>{{ $log->height }}</td>
                <td>{{ $log->steps }}</td>
                <td>{{ $log->food_name }}</td>
                <td>{{ $log->calories }}</td>
                <td>{{ $log->protein }}</td>
                <td>{{ $log->carbs }}</td>
                <td>{{ $log->fat }}</td>
                <td>{{ $log->sleep_hours }}</td>
                <td>{{ $log->heart_rate }}</td>
                <td>{{ $log->blood_pressure }}</td>
                <td>
                  <form action="{{ route('health.destroy', $log->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Graphiques --}}
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Calories & Macronutriments</h4>
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Poids dans le temps</h4>
        <canvas id="lineChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Répartition Eau / Calories</h4>
        <canvas id="doughnutChart"></canvas>
      </div>
    </div>
  </div>

</div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
const logs = @json($logs);

// Bar Chart : Calories et macronutriments
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: logs.map(l => l.date),
        datasets: [
            { label: 'Calories', data: logs.map(l => l.calories || 0), backgroundColor: '#f87979' },
            { label: 'Protein', data: logs.map(l => l.protein || 0), backgroundColor: '#7acbf9' },
            { label: 'Carbs', data: logs.map(l => l.carbs || 0), backgroundColor: '#f9d87a' },
            { label: 'Fat', data: logs.map(l => l.fat || 0), backgroundColor: '#7af97a' },
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Line Chart : Poids
const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: logs.map(l => l.date),
        datasets: [{
            label: 'Poids (kg)',
            data: logs.map(l => l.weight || 0),
            fill: false,
            borderColor: '#42a5f5',
            tension: 0.1
        }]
    },
    options: { responsive: true }
});

// Doughnut Chart : Eau vs Calories
const totalWater = logs.reduce((sum, l) => sum + (l.water || 0), 0);
const totalCalories = logs.reduce((sum, l) => sum + (l.calories || 0), 0);
const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Eau (ml)', 'Calories'],
        datasets: [{
            data: [totalWater, totalCalories],
            backgroundColor: ['#42f554','#f54242']
        }]
    },
    options: { responsive: true }
});
</script>
@endpush
