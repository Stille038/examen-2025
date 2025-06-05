<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aanwezigheid;

class AanwezigheidController extends Controller
{
    // Haalt alle aanwezigheidsdata op en geeft het terug als JSON
    public function index()
    {
        return response()->json(Aanwezigheid::all());
    }
}
