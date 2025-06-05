<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AanwezigheidController extends Controller
{
    public function index(Request $request)
    {
        // Filters ophalen of standaardwaarden instellen
        $filters = [
            'van_week' => $request->input('van_week', 1),
            'tot_week' => $request->input('tot_week', 52),
            'jaar' => $request->input('jaar', date('Y')),
        ];

        // Zorg dat van_week <= tot_week
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }

        // Simuleer aanwezigheidsdata per week (uit DB halen in praktijk)
        $aanwezigheidPerWeek = [];
        for ($week = $filters['van_week']; $week <= $filters['tot_week']; $week++) {
            $percentage = rand(0, 100);
            $aanwezigheidPerWeek[$week] = $percentage;
        }

        // Statistieken berekenen
        $totaalWeken = count($aanwezigheidPerWeek);
        $gemiddeldeAanwezigheid = $totaalWeken > 0 ? round(array_sum($aanwezigheidPerWeek) / $totaalWeken) : 0;
        $wekenOnder50 = count(array_filter($aanwezigheidPerWeek, fn($val) => $val < 50));
        $wekenBoven80 = count(array_filter($aanwezigheidPerWeek, fn($val) => $val >= 80));

        // Data naar view sturen
        return view('student-dashboard', [
            'filters' => $filters,
            'aanwezigheidPerWeek' => $aanwezigheidPerWeek,
            'stats' => [
                'gemiddelde' => $gemiddeldeAanwezigheid,
                'onder50' => $wekenOnder50,
                'boven80' => $wekenBoven80,
                'totaalWeken' => $totaalWeken,
            ],
        ]);
    }
}
