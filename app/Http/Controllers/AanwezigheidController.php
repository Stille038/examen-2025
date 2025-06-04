<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;

class AanwezigheidController extends Controller
{
    public function index()
    {
        $studentenData = Aanwezigheid::all()
            ->groupBy('studentnummer')
            ->map(function ($records, $studentnummer) {
                $totaal_weken = $records->count();

                if ($totaal_weken === 0) {
                    return [
                        'studentnummer' => $studentnummer,
                        'gemiddelde_aanwezigheid' => 'NVT',
                        'weken_boven_80' => 'NVT',
                        'weken_onder_50' => 'NVT',
                        'totaal_weken' => 'NVT',
                    ];
                }

                $gemiddelde = round($records->avg(function ($r) {
                    return $r->rooster > 0 ? ($r->aanwezigheid / $r->rooster) * 100 : 0;
                }));

                return [
                    'studentnummer' => $studentnummer,
                    'gemiddelde_aanwezigheid' => $gemiddelde . '%',
                    'weken_boven_80' => $records->filter(fn($r) => $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 > 80)->count(),
                    'weken_onder_50' => $records->filter(fn($r) => $r->rooster > 0 && ($r->aanwezigheid / $r->rooster) * 100 < 50)->count(),
                    'totaal_weken' => $totaal_weken,
                ];
            });

        return view('aanwezigheden.index', compact('studentenData'));
    }
}
