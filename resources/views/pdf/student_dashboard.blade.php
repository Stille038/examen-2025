<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Studentrapportage</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 10px;
        }

        .title {
            font-weight: bold;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <h1>Rapportage student: {{ $student->studentnummer }}</h1>
    <div class="box">
        <div class="title">Periode</div>
        {{ $filters['jaar'] }} | Week {{ $filters['van_week'] }} t/m {{ $filters['tot_week'] }}
    </div>

    <div class="box">
        <div class="title">Statistieken</div>
        Gemiddelde aanwezigheid: {{ $gemiddelde }}%<br>
        Totaal weken: {{ $totaal_weken }}<br>
        Weken onder 50%: {{ $weken_onder_50 }}<br>
        Weken boven 80%: {{ $weken_boven_80 }}<br>
        Rooster minuten: {{ $student->rooster }}<br>
        Aanwezig minuten: {{ $student->aanwezigheid }}
    </div>
</body>

</html> 
