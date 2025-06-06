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

        //  info uit front-end komt hier heen 
        $filters = [
            'van_week' => (int) $request->input('van_week', 1),
            'tot_week' => (int) $request->input('tot_week', 52),
            'jaar' => (int) $request->input('jaar', date('Y')),
        ];

        //  Corrigeer als van week tot week niet klopt, draaid die dat met elkaar zodat het wel klopt. 
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }

        // Pas filters toe op de query
        $records = Aanwezigheid::where('studentnummer', $studentnummer) // alle rijen van ingelogde student 
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']]) // filter zodat we data terug krijgen die gefilter is 
            ->where('jaar', $filters['jaar']) // dan filter die per jaar. 
            ->get(); // alle resultaten haalt die op binnen recors 

        //  Berekeningen op gefilterde data
        $totaal_weken = $records->count(); // 
        $gemiddelde = $records->avg(function ($r) {
            return $r->rooster > 0 ? ($r->aanwezigheid / $r->rooster) * 100 : 0;
        });

        $weken_boven_80 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 > 80;
        })->count();

        $weken_onder_50 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 < 50;
        })->count();

        //  Per-week percentages
        $aanwezigheidPerWeek = [];
        foreach ($records as $record) {
            if ($record->week && $record->rooster > 0) {
                $aanwezigheidPerWeek[$record->week] = round(($record->aanwezigheid / $record->rooster) * 100);
            }
        }

        //  Stuur alles naar de view
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
}
