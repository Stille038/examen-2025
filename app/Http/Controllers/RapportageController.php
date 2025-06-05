<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use PDF;

class RapportageController extends Controller
{
    public function studentPdf($studentnummer)
    {
        $student = Aanwezigheid::where('studentnummer', $studentnummer)->firstOrFail();
        $pdf = PDF::loadView('pdf.rapportage_student', compact('student'));
        return $pdf->download('rapportage_student_' . $studentnummer . '.pdf');
    }

    public function groepPdf($groepNaam)
    {
        $studenten = Aanwezigheid::where('groep', $groepNaam)->get();
        $pdf = PDF::loadView('pdf.rapportage_groep', compact('studenten', 'groepNaam'));
        return $pdf->download('rapportage_groep_' . $groepNaam . '.pdf');
    }
} 