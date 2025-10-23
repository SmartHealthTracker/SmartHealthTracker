<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Challenges PDF</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h2 { text-align: center; margin-bottom: 20px; color: #4b49ac; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #4b49ac; color: #fff; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .small-text { font-size: 0.85rem; color: #555; }
        .footer { text-align: center; font-size: 0.8rem; color: #777; margin-top: 30px; }
    </style>
</head>
<body>
    <h2>📋 Challenge Timetable</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Challenge Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($challenges as $index => $challenge)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $challenge->title }}</td>
                <td>{{ \Carbon\Carbon::parse($challenge->start)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($challenge->end)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Exported on {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>
</body>
</html>
