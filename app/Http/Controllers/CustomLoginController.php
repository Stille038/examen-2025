<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use Illuminate\Support\Facades\DB;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        //  Verwijder oude sessiegegevens om verwarring te voorkomen
        session()->forget(['studentnummer', 'docentnummer']);

        // kijken of het klopt
        $request->validate([
            'studentennummer' => 'required',
            'rollen' => 'required|in:student,docent',
        ]);

        $nummer = $request->input('studentennummer');
        $rol = $request->input('rollen');

        //  Check voor studenten
        if ($rol === 'student' && Aanwezigheid::where('studentnummer', $nummer)->exists()) {
            session(['studentnummer' => $nummer]);
            return redirect()->route('student-dashboard');
        }

        //  Check voor docenten
        if ($rol === 'docent' && DB::table('docenten')->where('docentnummer', $nummer)->exists()) {
            session(['docentnummer' => $nummer]);
            return redirect()->route('docent.dashboard');
        }

        //  Mislukt? Foutmelding terug
        return back()->withErrors([
            'studentennummer' => 'Ongeldig nummer of onjuiste rol.',
        ]);
    }
}
