<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;

class AanwezigheidController extends Controller
{
    public function index(Request $request)
    {
        $studentnummer = $request->session()->get('studentnummer');

        if (!$studentnummer) {
            return redirect('/login')->with('error', 'Je bent niet ingelogd.');
        }

        // 🔎 Haal filters op of stel defaults in
        $filters = [
            'van_week' => (int) $request->input('van_week', 1),
            'tot_week' => (int) $request->input('tot_week', 52),
            'jaar' => (int) $request->input('jaar', date('Y')),
        ];

        // 🔄 Corrigeer als weken verkeerd om zijn
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }

        // ✅ Filter toepassen op week & jaar
        $records = Aanwezigheid::where('studentnummer', $studentnummer)
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']])
            ->where('jaar', $filters['jaar'])
            ->get();

        $totaal_weken = $records->count();

        $gemiddelde = $records->avg(function ($r) {
            return $r->rooster > 0 ? ($r->aanwezigheid / $r->rooster) * 100 : 0;
        });

        $weken_boven_80 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 > 80;
        })->count();

        $weken_onder_50 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 < 50;
        })->count();

        // 📅 Aanwezigheid per week
        $aanwezigheidPerWeek = [];
        foreach ($records as $record) {
            if ($record->week && $record->rooster > 0) {
                $aanwezigheidPerWeek[$record->week] = round(($record->aanwezigheid / $record->rooster) * 100);
            }
        }

        // 🔁 Terugsturen naar de blade
        return view('student-dashboard', [
            'studentnummer' => $studentnummer,
            'student' => $records->first(),
            'aanwezigheidPerWeek' => $aanwezigheidPerWeek,
            'gemiddelde' => round($gemiddelde),
            'weken_boven_80' => $weken_boven_80,
            'weken_onder_50' => $weken_onder_50,
            'totaal_weken' => $totaal_weken,
            'filters' => $filters,
            'records' => $records,
        ]);
    }

    public function individueel(Request $request)
    {
        $studentnummer = $request->session()->get('studentnummer');

        if (!$studentnummer) {
            return redirect('/login')->with('error', 'Je bent niet ingelogd.');
        }

        // 🔎 Haal filters op of stel defaults in
        $filters = [
            'van_week' => (int) $request->input('van_week', 1),
            'tot_week' => (int) $request->input('tot_week', 52),
            'jaar' => (int) $request->input('jaar', date('Y')),
        ];

        // 🔄 Corrigeer als weken verkeerd om zijn
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }

        // ✅ Filter toepassen op week & jaar
        $records = Aanwezigheid::where('studentnummer', $studentnummer)
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']])
            ->where('jaar', $filters['jaar'])
            ->get();

        $totaal_weken = $records->count();

        $gemiddelde = $records->avg(function ($r) {
            return $r->rooster > 0 ? ($r->aanwezigheid / $r->rooster) * 100 : 0;
        });

        $weken_boven_80 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 > 80;
        })->count();

        $weken_onder_50 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 < 50;
        })->count();

        // 📅 Aanwezigheid per week
        $aanwezigheidPerWeek = [];
        foreach ($records as $record) {
            if ($record->week && $record->rooster > 0) {
                $aanwezigheidPerWeek[$record->week] = round(($record->aanwezigheid / $record->rooster) * 100);
            }
        }

        // 🔁 Terugsturen naar de blade
        return view('individueel-student', [
            'studentnummer' => $studentnummer,
            'student' => $records->first(),
            'aanwezigheidPerWeek' => $aanwezigheidPerWeek,
            'gemiddelde' => round($gemiddelde),
            'weken_boven_80' => $weken_boven_80,
            'weken_onder_50' => $weken_onder_50,
            'totaal_weken' => $totaal_weken,
            'filters' => $filters,
            'records' => $records,
        ]);
    }
}