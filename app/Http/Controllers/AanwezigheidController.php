<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use Carbon\Carbon; // Importeer Carbon voor datummanipulatie
// use Illuminate\Support\Facades\Log; // Verwijder de Log facade import

 
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
        // Haal *alle* historische records op om een lijst te krijgen van alle studenten die ooit data hebben gehad
        $alleRecordsHistorisch = Aanwezigheid::all();

        // Haal *alle* unieke groepsnamen op uit de database, gebaseerd op *alle* historische records
        $alleUniekeGroepNamen = $alleRecordsHistorisch->pluck('groep')->filter()->unique()->values()->toArray();
        // Voeg 'Onbekende Groep' toe als er records zijn zonder groep (in de historische data)
         if ($alleRecordsHistorisch->whereNull('groep')->count() > 0 || $alleRecordsHistorisch->where('groep', '')->count() > 0) {
              if (!in_array('Onbekende Groep', $alleUniekeGroepNamen)) {
                  $alleUniekeGroepNamen[] = 'Onbekende Groep';
              }
         }
        // Sorteer de groepsnamen voor weergave in de filter en vergelijkingstabel
        sort($alleUniekeGroepNamen);

        // Haal de meest recente records per student op voor de hoofdlijst en statistieken
        $alleRecordsRecent = Aanwezigheid::orderBy('created_at', 'desc')->get();

        $uniqueStudenten = $alleRecordsRecent->groupBy('studentnummer')->map(function ($studentRecords) {
            // Neem de eerste (meest recente) record van de gegroepeerde records
            $latestRecord = $studentRecords->first();
            
            // Bereken percentage en status voor deze student
            $latestRecord->percentage = $latestRecord->rooster > 0 ? 
                round(($latestRecord->aanwezigheid / $latestRecord->rooster) * 100) : 0;
            $latestRecord->status = $latestRecord->rooster == 0 ? 'Gestopt' : 
                ($latestRecord->percentage < 50 ? 'Risico' : 'Actief');
            $latestRecord->laatste_week = 'N/A';
            
            return $latestRecord;
        })->values(); // Convert to array of unique student records

        // Bereken groepsstatistieken met de unieke studenten MET RECENTE DATA
        $groepenMetRecenteData = $uniqueStudenten->groupBy('groep')->map(function ($groepStudenten, $groepNaam) {
            $aantal = $groepStudenten->count();
            $gemiddelde = $aantal > 0 ? round($groepStudenten->avg(function ($s) {
                return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0;
            }), 0) : 0;

            // Geef een naam aan groepen zonder naam (null of lege string)
            $displayGroepNaam = empty($groepNaam) ? 'Onbekende Groep' : $groepNaam;

            return [
                'naam' => $displayGroepNaam,
                'gemiddelde' => $gemiddelde,
                'aantal' => $aantal,
            ];
        })->values()->keyBy('naam')->toArray(); // Key by naam for easier lookup

        // Combineer alle unieke groepsnamen (inclusief die zonder recente data) met de berekende statistieken
        // Gebruik de complete lijst $alleUniekeGroepNamen voor de weergave
        $groepenVoorView = [];
        foreach ($alleUniekeGroepNamen as $groepNaam) {
            $displayGroepNaam = empty($groepNaam) ? 'Onbekende Groep' : $groepNaam;
            // Zoek de statistieken op basis van de *complete* lijst van groepsnamen
            // Als een groep geen recente data had, zijn de stats 0/0
            $stats = $groepenMetRecenteData[$displayGroepNaam] ?? ['naam' => $displayGroepNaam, 'gemiddelde' => 0, 'aantal' => 0];
            $groepenVoorView[] = $stats;
        }

        // Bereken algemene statistieken met de unieke studenten
        $aantalStudenten = $uniqueStudenten->count();
        $gemiddelde = $aantalStudenten > 0 ? round($uniqueStudenten->avg(function($s) { 
            return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0; 
        }), 0) : 0;
        
        $risico = $uniqueStudenten->filter(function($s) { 
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 < 50; 
        })->count();
        
        $top = $uniqueStudenten->filter(function($s) { 
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 > 80; 
        })->count();
        
        $gestopt = $uniqueStudenten->filter(function($s) { 
            return $s->rooster == 0; 
        })->count();

        return [
            'studenten' => $uniqueStudenten, // Gebruik de unieke studenten voor de initiële Alpine.js data
            'groepen' => $groepenVoorView,
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

    // Nieuwe methode om alle studenten op te halen voor het logboek - Herstel deze ook
    public function getAllStudentData()
    {
        $alleStudenten = Aanwezigheid::all();
        
        foreach ($alleStudenten as $student) {
            // Behoud originele velden
            $student->aanwezigheid = $student->aanwezigheid;
            $student->rooster = $student->rooster;
            
            // Bereken percentage correct
            $student->percentage = $student->rooster > 0 ? 
                round(($student->aanwezigheid / $student->rooster) * 100) : 0;
            
            // Bepaal status op basis van percentage en rooster
            if ($student->rooster == 0) {
                $student->status = 'Gestopt';
            } elseif ($student->percentage < 50) {
                $student->status = 'Risico';
            } else {
                $student->status = 'Actief';
            }
            
            // Voeg laatste week toe voor logboek
            $student->laatste_week = 'N/A';
        }
        
        return response()->json($alleStudenten);
    }
}