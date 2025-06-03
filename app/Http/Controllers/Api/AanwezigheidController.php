<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aanwezigheid;

class AanwezigheidController extends Controller
{
    public function index()
    {
        return response()->json(Aanwezigheid::all());
    }
}
