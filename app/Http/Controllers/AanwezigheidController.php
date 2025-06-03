<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid; 

class AanwezigheidController extends Controller
{
    public function index()
    {
        $aanwezigheden = Aanwezigheid::all();
        return view('aanwezigheden.index', compact('aanwezigheden'));
    }
}
