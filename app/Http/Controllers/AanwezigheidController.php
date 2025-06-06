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
 
        $records = Aanwezigheid::where('studentnummer', $studentnummer)->get();
 
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
 
        // 📅 Bereken aanwezigheid per week (optioneel voor grafiek/tabel)
        $aanwezigheidPerWeek = [];
        foreach ($records as $record) {
            if ($record->week && $record->rooster > 0) {
                $aanwezigheidPerWeek[$record->week] = round(($record->aanwezigheid / $record->rooster) * 100);
            }
        }
 
        // 📤 Data naar de view sturen
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