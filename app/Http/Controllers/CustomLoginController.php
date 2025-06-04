<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Valideer de invoer van het formulier
        $request->validate([
            'studentennummer' => 'required',
            'rollen' => 'required',
        ]);

        // ➤ Haal inputwaarden op
        $studentnummer = $request->input('studentennummer');
        $rol = $request->input('rollen');

        // ➤ Als rol is 'student' én het studentnummer bestaat in de database
        if ($rol === 'student' && Aanwezigheid::where('studentnummer', $studentnummer)->exists()) {
            // ➤ Stuur door naar student-dashboard met session data (optioneel)
            return redirect()->route('student-dashboard')->with('studentnummer', $studentnummer);
        }

        // ❌ Foutmelding terugsturen als check mislukt
        return back()->withErrors([
            'studentennummer' => 'Ongeldig studentnummer of onjuiste rol.',
        ]);
    }
}
