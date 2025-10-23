<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'Activités - SmartHealth Tracker</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #333;
        }
        header {
            text-align: center;
            border-bottom: 2px solid #00bfa5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        h1 {
            color: #00bfa5;
            font-size: 22px;
            margin: 0;
        }
        h3 {
            margin-top: 10px;
            color: #555;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary div {
            display: inline-block;
            width: 45%;
            margin: 10px 2%;
            background: #f7f7f7;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .chart {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #00bfa5;
            color: #fff;
            padding: 8px;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            margin-top: 30px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1>SmartHealth Tracker</h1>
    <h3>Rapport d'Activité Personnalisé</h3>
    <p>Période : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
</header>

<section class="summary">
    <div>
        <h2>{{ $totalCalories }}</h2>
        <p>Calories Brûlées</p>
    </div>
    <div>
        <h2>{{ round($totalHours, 2) }} h</h2>
        <p>Heures d'Activité</p>
    </div>
</section>

@if(!empty($chartUrl))
<section class="chart">
    <img src="{{ $chartUrl }}" alt="Graphique des activités" style="width: 100%; max-width: 600px;">
</section>
@endif

<section>
    <h3>Détails des Activités</h3>
    <table>
        <thead>
            <tr>
                <th>Activité</th>
                <th>Durée (min)</th>
                <th>Calories Brûlées</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->activity->name ?? 'N/A' }}</td>
                <td>{{ $log->duration }}</td>
                <td>{{ $log->calories_burned }}</td>
                <td>{{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

<footer>
    Généré automatiquement par SmartHealth Tracker © {{ date('Y') }}  
</footer>
</body>
</html>
