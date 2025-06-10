<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AanwezigheidController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\GroupController;


Route::get('/debug-route', function () {
    return response()->json(['✅ API is working']);
});



Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);
// 

Route::get('/students', [StudentController::class, 'index']);


Route::get('/groups', [GroupController::class, 'index']);
