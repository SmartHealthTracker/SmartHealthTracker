<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du Temps des Challenges</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #f4f5f7;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4b49ac;
            font-size: 1.8rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
            font-size: 0.95rem;
        }
        th {
            background-color: #4b49ac;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e0e0ff;
        }
        .footer {
            text-align: center;
            font-size: 0.85rem;
            color: #777;
            margin-top: 25px;
        }
        .status-open {
            color: green;
            font-weight: bold;
        }
        .status-full {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Emploi du Temps des Challenges</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom du Challenge</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Participations</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($challenges as $index => $challenge)
            @php
                $participations = $challenge->participations_count ?? $challenge->participations()->count() ?? 0;
                $status = $participations > 5 ? 'Complet' : 'Ouvert';
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $challenge->name ?? $challenge->title }}</td>
                <td>{{ \Carbon\Carbon::parse($challenge->start_date ?? $challenge->start)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($challenge->end_date ?? $challenge->end ?? $challenge->start)->format('d M Y') }}</td>
                <td>{{ $participations }}</td>
                <td class="{{ $status === 'Complet' ? 'status-full' : 'status-open' }}">{{ $status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Exporté le {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>
</body>
</html>
