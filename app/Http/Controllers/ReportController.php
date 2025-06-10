<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
// use App\Models\Student; // Mogelijk nodig, afhankelijk van hoe studenten worden opgehaald

class ReportController extends Controller
{
    /**
     * Download groepsrapportage als PDF.
     *
     * @param  string  $groepnaam De naam van de groep.
     * @return \Illuminate\Http\Response
     */
    public function downloadGroupReportPdf($groepnaam)
    {
        try {
            // Haal alle studenten op voor deze groep
            $studenten = Aanwezigheid::where('groep', $groepnaam)
                ->select('studentnummer', 'aanwezigheid', 'rooster')
                ->get()
                ->map(function ($student) {
                    $student->percentage = $student->rooster > 0 ? round(($student->aanwezigheid / $student->rooster) * 100) : 0;
                    $student->status = $student->rooster == 0 ? 'Gestopt' : ($student->percentage < 50 ? 'Risico' : 'Actief');
                    return $student;
                });

            if ($studenten->isEmpty()) {
                return response()->json(['error' => 'Geen studenten gevonden voor deze groep'], 404);
            }

            // Bereken groepsstatistieken
            $groepGemiddelde = round($studenten->avg('percentage'));
            $risicoStudenten = $studenten->where('status', 'Risico')->count();
            $topStudenten = $studenten->where('percentage', '>', 80)->count();

            $pdf = PDF::loadView('pdf.rapportage_groep', [
                'groepnaam' => $groepnaam,
                'studenten' => $studenten,
                'groepGemiddelde' => $groepGemiddelde,
                'risicoStudenten' => $risicoStudenten,
                'topStudenten' => $topStudenten
            ]);

            $pdf->setPaper('a4');
            return $pdf->download("groepsrapportage-{$groepnaam}.pdf");
        } catch (\Exception $e) {
            Log::error('Fout bij genereren groepsrapportage: ' . $e->getMessage());
            return response()->json(['error' => 'Kon groepsrapportage niet genereren: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download individuele student rapportage als PDF.
     *
     * @param  string  $studentnummer Het studentnummer.
     * @return \Illuminate\Http\Response
     */
    public function downloadStudentReportPdf($studentnummer)
    {
        try {
            // Haal de meest recente aanwezigheidsdata op voor deze student
            $studentData = Aanwezigheid::where('studentnummer', $studentnummer)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$studentData) {
                return response('Geen data gevonden voor student: ' . $studentnummer, 404);
            }

            // Bereken percentage en status
            $percentage = $studentData->rooster > 0 ?
                round(($studentData->aanwezigheid / $studentData->rooster) * 100) : 0;
            $status = $studentData->rooster == 0 ? 'Gestopt' : ($percentage < 50 ? 'Risico' : 'Actief');

            // Voeg berekende waarden toe aan het student object
            $studentData->percentage = $percentage;
            $studentData->status = $status;

            // Genereer de PDF
            $pdf = PDF::loadView('pdf.rapportage_student', [
                'student' => $studentData
            ]);

            // Stel papierformaat in
            $pdf->setPaper('a4');

            // Download de PDF
            return $pdf->download('studentrapportage_' . $studentnummer . '_' . date('Ymd') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Fout bij genereren PDF studentrapportage: ' . $e->getMessage(), [
                'studentnummer' => $studentnummer,
                'error' => $e
            ]);
            return response('Kon studentrapportage niet genereren: ' . $e->getMessage(), 500);
        }
    }
    public function downloadEigenStudentReport($studentnummer) //student pdf 
    {
        // Filters op weken
        $filters = [
            'jaar' => request('jaar', 2024),
            'van_week' => request('van_week', 1),
            'tot_week' => request('tot_week', 52),
        ];


        // Haal de juiste filter resulateten op 
        $records = Aanwezigheid::where('studentnummer', $studentnummer)
            ->where('jaar', $filters['jaar'])
            ->whereBetween('week', [$filters['van_week'], $filters['tot_week']])
            ->get();

        if ($records->isEmpty()) {
            return response('Geen data gevonden voor student: ' . $studentnummer, 404);
        }

        // Eerste record als basis voor info (zoals naam, studentnummer etc. first maakt object van en geen array dus kan aangeroepen worden)
        $student = $records->first();

        // aanwezigheid berekenen
        $gemiddelde = round($records->avg(function ($r) { // avg maakt gemiddelde van gefilterde gegevens 
            return $r->rooster > 0 ? ($r->aanwezigheid / $r->rooster) * 100 : 0;
        }));

        $totaalRooster = $records->sum('rooster');
        $totaalAanwezig = $records->sum('aanwezigheid');

        $wekenBoven80 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 > 80;
        })->count();

        $wekenOnder50 = $records->filter(function ($r) {
            return $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 < 50;
        })->count();

        // PDF genereren
        $pdf = PDF::loadView('pdf.student_dashboard', [
            'student' => $student,
            'filters' => $filters,
            'gemiddelde' => $gemiddelde,
            'totaal_weken' => $records->count(),
            'weken_boven_80' => $wekenBoven80,
            'weken_onder_50' => $wekenOnder50,
            'rooster' => $totaalRooster,
            'aanwezig' => $totaalAanwezig,
        ]);

        return $pdf->download('eigen_studentrapport_' . $studentnummer . '.pdf');
    }
}