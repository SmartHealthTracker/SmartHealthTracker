@extends('layout.master')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="row">

  {{-- Formulaire pour ajouter un HealthLog --}}
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Ajouter un log santé</h4>
        <form action="{{ route('health.store') }}" method="POST" novalidate>
          @csrf
          <div class="row">
            <div class="col-md-2">
              <input type="number" name="water" class="form-control @error('water') is-invalid @enderror" placeholder="Eau (ml)" value="{{ old('water') }}" min="0" max="10000" required>
              @error('water')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="number" step="0.1" name="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="Poids (kg)" value="{{ old('weight') }}" min="0.1" max="250" required>
              @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="number" step="0.1" name="height" class="form-control @error('height') is-invalid @enderror" placeholder="Taille (cm)" value="{{ old('height') }}" min="30" max="230" required>
              @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="number" name="steps" class="form-control @error('steps') is-invalid @enderror" placeholder="Pas" value="{{ old('steps') }}" min="0" max="50000" required>
              @error('steps')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="text" name="food_name" class="form-control @error('food_name') is-invalid @enderror" placeholder="Aliment" value="{{ old('food_name') }}" required>
              @error('food_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-1">
              <input type="number" name="calories" class="form-control @error('calories') is-invalid @enderror" placeholder="Cal" value="{{ old('calories') }}" min="0" required>
              @error('calories')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-1">
              <input type="number" name="protein" class="form-control @error('protein') is-invalid @enderror" placeholder="Prot" value="{{ old('protein') }}" min="0" required>
              @error('protein')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-1">
              <input type="number" name="carbs" class="form-control @error('carbs') is-invalid @enderror" placeholder="Carb" value="{{ old('carbs') }}" min="0" required>
              @error('carbs')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-1">
              <input type="number" name="fat" class="form-control @error('fat') is-invalid @enderror" placeholder="Fat" value="{{ old('fat') }}" min="0" required>
              @error('fat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-2">
              <input type="number" name="sleep_hours" class="form-control @error('sleep_hours') is-invalid @enderror" placeholder="Sommeil (h)" value="{{ old('sleep_hours') }}" min="0" max="24" required>
              @error('sleep_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="number" name="heart_rate" class="form-control @error('heart_rate') is-invalid @enderror" placeholder="FC (bpm)" value="{{ old('heart_rate') }}" min="30" max="220" required>
              @error('heart_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
              <input type="text" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" placeholder="TA (120/80)" value="{{ old('blood_pressure') }}" required>
              @error('blood_pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <button type="submit" class="btn btn-primary mt-2">Ajouter</button>
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
                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Supprimer</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
const logs = @json($logs);

// Popups pour erreurs et succès
@if ($errors->any())
let errors = @json($errors->all());
errors.forEach(err => {
    Swal.fire({
        icon: 'error',
        title: 'Erreur de saisie',
        text: err,
        toast: true,
        position: 'top-end',
        timer: 4000,
        timerProgressBar: true,
        showConfirmButton: false
    });
});
@endif

@if (session('success'))
Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: '{{ session('success') }}',
    toast: true,
    position: 'top-end',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false
});
@endif

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
