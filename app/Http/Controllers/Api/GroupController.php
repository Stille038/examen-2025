<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Student::distinct()->pluck('groep')->filter();
        return response()->json($groups);
    }
}
