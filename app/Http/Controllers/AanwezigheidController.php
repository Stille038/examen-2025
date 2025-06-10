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
    
        // Haal filters op of stel defaults in
        $filters = [
            'van_week' => (int) $request->input('van_week', 1),
            'tot_week' => (int) $request->input('tot_week', 52),
            'jaar' => $request->input('jaar') // kan null zijn
        ];
    
        // Corrigeer als weken verkeerd om zijn
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }
    
        // Start met de basisquery
        $query = Aanwezigheid::where('studentnummer', $studentnummer)
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']]);
    
        // Alleen filteren op jaar als de gebruiker er zelf een heeft gekozen
        if (!empty($filters['jaar'])) {
            $query->where('jaar', $filters['jaar']);
        }
    
        // Haal de records op
        $records = $query->get();
    
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
    
        $aanwezigheidPerWeek = $records->filter(function ($record) {
            return $record->week && $record->rooster > 0;
        });
    
        $heeftKritiek = $records->contains(function ($record) {
            return $record->categorie === 'Kritiek';
        });
    
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
            'heeftKritiek' => $heeftKritiek,
        ]);
    }

    public function individueel(Request $request, $studentnummer = null)
    {
        if (!$studentnummer) {
            $studentnummer = $request->session()->get('studentnummer');
        }

        if (!$studentnummer) {
            return redirect('/login')->with('error', 'Je bent niet ingelogd.');
        }

        // Haal filters op of stel defaults in
        $filters = [
            'van_week' => (int) $request->input('van_week', 1),
            'tot_week' => (int) $request->input('tot_week', 52),
            'jaar' => (int) $request->input('jaar', date('Y')),
        ];

        // Corrigeer als weken verkeerd om zijn
        if ($filters['van_week'] > $filters['tot_week']) {
            [$filters['van_week'], $filters['tot_week']] = [$filters['tot_week'], $filters['van_week']];
        }

        // Filter toepassen op week & jaar
        $records = Aanwezigheid::where('studentnummer', $studentnummer)
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']])
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

        // Aanwezigheid per week
        $aanwezigheidPerWeek = [];
        foreach ($records as $record) {
            if ($record->week && $record->rooster > 0) {
                $aanwezigheidPerWeek[] = $record;
            }
        }

        $heeftKritiek = $records->contains(function ($record) {
            return $record->categorie === 'Kritiek';
        });

        // Terugsturen naar de blade
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
            'heeftKritiek' => $heeftKritiek,
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
            $latestRecord->status = $latestRecord->rooster == 0 ? 'Gestopt' : ($latestRecord->percentage < 50 ? 'Risico' : 'Actief');
            $latestRecord->laatste_week = 'N/A'; // Behoud N/A voor consistentie

            return $latestRecord;
        })->values(); // Convert to array of unique student records

        // Bereken groepsstatistieken met de unieke studenten MET RECENTE DATA
        // We gebruiken $uniqueStudenten (gebaseerd op recente data) voor de berekeningen
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
        $gemiddelde = $aantalStudenten > 0 ? round($uniqueStudenten->avg(function ($s) {
            return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0;
        }), 0) : 0;

        $risico = $uniqueStudenten->filter(function ($s) {
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 < 50;
        })->count();

        $top = $uniqueStudenten->filter(function ($s) {
            return $s->rooster && ($s->aanwezigheid / $s->rooster) * 100 > 80;
        })->count();

        $gestopt = $uniqueStudenten->filter(function ($s) {
            return $s->rooster == 0;
        })->count();

        // Retourneer de view met de data
        return view('docent_dashboard', [
            'studenten' => $uniqueStudenten, // Gebruik de unieke studenten voor de initiÃ«le Alpine.js data
            'groepen' => $groepenVoorView,
            'gemiddelde' => $gemiddelde,
            'risico' => $risico,
            'top' => $top,
            'gestopt' => $gestopt,
            'aantalStudenten' => $aantalStudenten
        ]);
    }

    public function stopStudying($studentnummer)
    {
        try {
            $updated = Aanwezigheid::where('studentnummer', $studentnummer)
                ->update(['rooster' => 0]);

            if ($updated) {
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

        foreach ($alleStudenten as $student) {
            $student->percentage = $student->rooster > 0 ?
                round(($student->aanwezigheid / $student->rooster) * 100) : 0;
            $student->status = $student->rooster == 0 ? 'Gestopt' : ($student->percentage < 50 ? 'Risico' : 'Actief');
            // Voeg hier weer 'laatste_week' met 'N/A' toe voor de logboek modal
            $student->laatste_week = 'N/A';
        }
        return response()->json($alleStudenten);
    }
}