<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Groepsrapportage - {{ $groepnaam }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .status-risico {
            color: #dc2626;
        }
        .status-actief {
            color: #059669;
        }
        .status-gestopt {
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Groepsrapportage</h1>
        <p>Groep: {{ $groepnaam }}</p>
        <p>Datum: {{ now()->format('d-m-Y') }}</p>
    </div>

    <div class="summary">
        <h2>Groepsoverzicht</h2>
        <p>Gemiddelde aanwezigheid: {{ $groepGemiddelde }}%</p>
        <p>Aantal studenten: {{ count($studenten) }}</p>
        <p>Risicostudenten (< 50%): {{ $risicoStudenten }}</p>
        <p>Toppresteerders (> 80%): {{ $topStudenten }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Studentnummer</th>
                <th>Aanwezigheid</th>
                <th>Rooster</th>
                <th>Percentage</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studenten as $student)
            <tr>
                <td>{{ $student->studentnummer }}</td>
                <td>{{ $student->aanwezigheid }}</td>
                <td>{{ $student->rooster }}</td>
                <td>{{ $student->percentage }}%</td>
                <td class="status-{{ strtolower($student->status) }}">{{ $student->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dit rapport is automatisch gegenereerd op {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html> 