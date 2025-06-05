<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AanwezigheidController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\CustomLoginController;
use App\Models\Aanwezigheid;

// ➤ Login functionaliteit op basis van studentnummer en rol
Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');

Route::redirect('/', '/login'); // redirect naar login pagina   

// ➤ Student dashboard (zonder checkStudent middleware)
Route::get('/student-dashboard', function () {
    $studentnummer = session('studentnummer');
    $studentenData = Aanwezigheid::where('studentnummer', $studentnummer)->get();
    return view('student-dashboard', compact('studentenData'));
})->name('student-dashboard');

Route::view('/individueel-student', 'individueel-student')->name('individueel-student');

// ➤ Route voor alle berekende statistieken (docent-overzicht)
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index'])->name('aanwezigheden.index');

// ➤ Optioneel: individuele student route (op basis van studentnummer)
Route::get('/student/{studentnummer}', [AanwezigheidController::class, 'show'])->name('student.show');

// ➤ Losse pagina's (voorkant)
Route::view('/test1', 'test1')->name('test1');
Route::view('/test2', 'test2')->name('test2');
Route::view('/test', 'test')->name('test');

// Aangepaste route voor docent-dashboard met data
Route::get('/docent/dashboard', function () {
    $studenten = Aanwezigheid::all();
    return view('docent_dashboard', compact('studenten'));
})->name('docent.dashboard');

Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// ➤ Excel upload
Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

// ➤ Profielroutes (alleen voor ingelogde gebruikers via standaard Laravel auth)
require __DIR__ . '/auth.php';
