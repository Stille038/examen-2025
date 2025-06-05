<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Aanwezigheid;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\RapportageController;
use App\Http\Controllers\AanwezigheidController;
use App\Http\Controllers\CustomLoginController;


Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);
Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');
Route::redirect('/', '/login');

Route::get('/', function () {
    $studenten = Aanwezigheid::all(); // haal alle studentgegevens op
    return view('dashboard', compact('studenten'));
})->name('dashboard');

Route::get('/student-dashboard', function () {
    return view('student-dashboard');
})->name('student-dashboard');
Route::get('/individueel-student', function () {
    return view('individueel-student');
})->name('individueel-student');
Route::get('/test1', function () {
    return view('test1');
})->name('test1');

Route::get('/test2', function () {
    return view('test2');
})->name('test2');

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/docent/dashboard', function () {
    $studenten = \App\Models\Aanwezigheid::all();
    // Dynamisch: groepeer op groep en bereken gemiddelden
    $groepen = $studenten->groupBy('groep')->map(function ($groepStudenten, $groepNaam) {
        $aantal = $groepStudenten->count();
        $gemiddelde = $aantal > 0 ? round($groepStudenten->avg(function ($s) {
            return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0;
        }), 0) : 0;
        return [
            'naam' => $groepNaam,
            'gemiddelde' => $gemiddelde,
            'aantal' => $aantal,
        ];
    })->values();
    return view('docent_dashboard', compact('studenten', 'groepen'));
})->name('docent.dashboard');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

// PDF Rapportage Routes
Route::get('/docent/rapportage/student/{studentnummer}', [RapportageController::class, 'studentPdf'])->name('rapportage.student.pdf');
Route::get('/docent/rapportage/groep/{groepNaam}', [RapportageController::class, 'groepPdf'])->name('rapportage.groep.pdf');

require __DIR__ . '/auth.php';
