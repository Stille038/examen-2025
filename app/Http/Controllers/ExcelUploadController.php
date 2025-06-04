<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelUploadController extends Controller //aanmaken van een nieuwe controller 
{
    public function store(Request $request) // Deze functie voert de logica uit wanneer het formulier wordt ingediend
    {
        $request->validate([
            'bestand' => 'required|mimes:xlsx,ods|max:2048', //als bestand klopt gaat die logica uitvoeren, momenteel geen logica alleen succes melding sturen.
        ]);


        return back()->with('success', 'Bestand ontvangen! (maar nog niet verwerkt)');
    }
}
