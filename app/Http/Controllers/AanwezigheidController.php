<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
 
class AanwezigheidController extends Controller
{
    public function index(Request $request)
    {
        // Check of het een docent of student dashboard is
        $isDocent = $request->session()->has('docentnummer');
        
        if ($isDocent) {
            return $this->getDocentDashboardData();
        }

        // Student dashboard logica
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

    public function getDocentDashboardData()
    {
        // Haal alle studenten op voor de initiële dataset in de view
        $studenten = Aanwezigheid::all();

        // Bereken groepsstatistieken
        // Gebruik alle studenten voor groepsstatistieken, inclusief gestopte
        $alleStudenten = Aanwezigheid::all();
        $groepen = $alleStudenten->groupBy('groep')->map(function ($groepStudenten, $groepNaam) {
            $aantal = $groepStudenten->count();
            $gemiddelde = $aantal > 0 ? round($groepStudenten->avg(function ($s) {
                return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0;
            }), 0) : 0;

            return [
                'naam' => $groepNaam,
                'gemiddelde' => $gemiddelde,
                'aantal' => $aantal,
            ];
        })->values();

        // Bereken algemene statistieken
        // Gebruik alle studenten voor algemene statistieken
        $aantalStudenten = $alleStudenten->count();
        $gemiddelde = $aantalStudenten > 0 ? round($alleStudenten->avg(function($s) { 
            return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0; 
        }), 0) : 0;
        
        $risico = $alleStudenten->filter(function($s) { 
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 < 50; 
        })->count();
        
        $top = $alleStudenten->filter(function($s) { 
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 > 80; 
        })->count();
        
        $gestopt = $alleStudenten->filter(function($s) { 
            return $s->rooster == 0; 
        })->count();

        // Voeg extra berekeningen toe die in de view worden gebruikt
        foreach ($studenten as $student) {
            $student->percentage = $student->rooster > 0 ? 
                round(($student->aanwezigheid / $student->rooster) * 100) : 0;
            $student->status = $student->rooster == 0 ? 'Gestopt' : 
                ($student->percentage < 50 ? 'Risico' : 'Actief');
        }

        return [
            'studenten' => $studenten, // Alle studenten voor de initiële Alpine.js data
            'groepen' => $groepen,
            'gemiddelde' => $gemiddelde,
            'risico' => $risico,
            'top' => $top,
            'gestopt' => $gestopt,
            'aantalStudenten' => $aantalStudenten
        ];
    }

    public function stopStudying($studentnummer)
    {
        try {
            // Vind de aanwezigheidsrecords voor de student
            $updated = Aanwezigheid::where('studentnummer', $studentnummer)
                               ->update(['rooster' => 0]);

            if ($updated) {
                // Optioneel: log de actie hier als dat nodig is in een apart logsysteem
                return response()->json(['success' => true, 'message' => 'Student gemarkeerd als gestopt.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Geen records gevonden of bijgewerkt voor deze student.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Er ging iets mis: ' . $e->getMessage()], 500);
        }
    }

    // Nieuwe methode om alle studenten op te halen voor het logboek
    public function getAllStudentData()
    {
        $alleStudenten = Aanwezigheid::all();
        // Voeg extra berekeningen toe die in de view worden gebruikt
        foreach ($alleStudenten as $student) {
            $student->percentage = $student->rooster > 0 ? 
                round(($student->aanwezigheid / $student->rooster) * 100) : 0;
            $student->status = $student->rooster == 0 ? 'Gestopt' : 
                ($student->percentage < 50 ? 'Risico' : 'Actief');
        }
        return response()->json($alleStudenten);
    }
}