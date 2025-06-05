<!DOCTYPE html>
<html>
<head>
    <title>Studentrapportage</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- Tailwind CSS equivalent styles for PDF --}}
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1a202c; /* Equivalent to text-gray-900 */
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 24px; /* Equivalent to p-6 */
            margin: 24px; /* Equivalent to m-6 or mx-auto with some margin */
            background-color: #ffffff; /* Equivalent to bg-white */
            border: 1px solid #e2e8f0; /* Equivalent to border border-gray-200 */
            border-radius: 8px; /* Equivalent to rounded-lg */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Equivalent to shadow-md */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0; /* subtle separator */
        }
        .header h1 {
            font-size: 24px; /* Equivalent to text-2xl */
            font-weight: 600; /* Equivalent to font-semibold */
            color: #1a202c; /* Equivalent to text-gray-800 */
            margin: 0;
        }
        .student-details p {
            margin: 12px 0; /* Equivalent to my-3 or my-4 */
            font-size: 14px; /* Equivalent to text-sm */
            color: #4a5568; /* Equivalent to text-gray-700 */
        }
        .student-details p strong {
            color: #2d3748; /* Equivalent to text-gray-800 */
        }
        .status {
            font-weight: 600; /* Equivalent to font-semibold */
            padding: 4px 8px; /* Equivalent to px-2 py-1 */
            border-radius: 4px; /* Equivalent to rounded */
            display: inline-block;
            font-size: 12px; /* Equivalent to text-sm */
        }
        .status.risico {
            background-color: #fee2e2; /* Equivalent to bg-red-100 */
            color: #c53030; /* Equivalent to text-red-800 */
        }
        .status.actief {
            background-color: #c6f6d5; /* Equivalent to bg-green-100 */
            color: #2f855a; /* Equivalent to text-green-800 */
        }
         .status.gestopt {
            background-color: #edf2f7; /* Equivalent to bg-gray-100 */
            color: #4a5568; /* Equivalent to text-gray-700 */
        }
         /* Table styles based on the dashboard table */
         table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
         }
         th,
         td {
            border: 1px solid #e2e8f0; /* Equivalent to border-gray-200 */
            padding: 12px 16px; /* Equivalent to px-4 py-3 */
            text-align: left;
         }
         th {
            background-color: #edf2f7; /* Equivalent to bg-gray-50 */
            font-weight: 600; /* Equivalent to font-medium */
            color: #718096; /* Equivalent to text-gray-500 */
            text-transform: uppercase;
            font-size: 10px; /* Equivalent to text-xs */
         }
         td {
            font-size: 14px; /* Equivalent to text-sm */
            color: #2d3748; /* Equivalent to text-gray-900 */
         }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Rapportage voor {{ $student->naam ?? $student->studentnummer }}</h1>
        </div>

        <div class="student-details">
            <p><strong>Studentnummer:</strong> {{ $student->studentnummer }}</p>
            <p><strong>Groep:</strong> {{ $student->groep ?? 'Onbekend' }}</p>
            <p><strong>Aanwezigheid:</strong> {{ $student->aanwezigheid ?? 0 }} minuten</p>
            <p><strong>Rooster:</strong> {{ $student->rooster ?? 0 }} minuten</p>
            @php
                $gem = $student->rooster && $student->rooster > 0 ? round(($student->aanwezigheid / $student->rooster) * 100) : 0;
                $status = $student->rooster == 0 ? 'Gestopt' : ($gem < 50 ? 'Risico' : 'Actief');
                $statusClass = $student->rooster == 0 ? 'gestopt' : ($gem < 50 ? 'risico' : 'actief');
            @endphp
            <p><strong>Gemiddelde Aanwezigheid:</strong> {{ $gem }}%</p>
            <p><strong>Status:</strong> <span class="status {{ $statusClass }}">{{ $status }}</span></p>

            {{-- Voeg hier eventueel meer details of een tabel met weekdata toe --}}

        </div>
    </div>
</body>
</html> 