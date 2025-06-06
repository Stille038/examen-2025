<?php

use Illuminate\Support\Facades\Route;
use App\Models\Aanwezigheid;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\RapportageController;
use App\Http\Controllers\AanwezigheidController;
use App\Http\Controllers\CustomLoginController;

// ➤ Login
Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');

// ➤ Startpagina → dashboard met alle studenten (voor docenten)


// ➤ Student-dashboard (via controller met filters en statistieken)
Route::get('/student-dashboard', [AanwezigheidController::class, 'index'])->name('student-dashboard');

// ➤ Losse weergavepagina's
Route::view('/individueel-student', 'individueel-student')->name('individueel-student');
Route::view('/test1', 'test1')->name('test1');
Route::view('/test2', 'test2')->name('test2');
Route::view('/test', 'test')->name('test');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// ➤ Docent-dashboard met groepsstatistieken
Route::get('/docent/dashboard', function () {
    $aanwezigheidController = app(AanwezigheidController::class);
    $data = $aanwezigheidController->getDocentDashboardData();
    return view('docent_dashboard', $data);
})->name('docent.dashboard');

// ➤ Route om student te markeren als gestopt
Route::post('/docent/student/{studentnummer}/stop', [AanwezigheidController::class, 'stopStudying'])->name('docent.student.stop');

// ➤ Route om alle studenten op te halen voor het logboek
Route::get('/docent/students/all', [AanwezigheidController::class, 'getAllStudentData'])->name('docent.students.all');

// ➤ Aanwezigheden index (alle studenten)
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);

// ➤ Excel upload
Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

// ➤ Rapportage PDF
Route::get('/docent/rapportage/student/{studentnummer}', [RapportageController::class, 'studentPdf'])->name('rapportage.student.pdf');
Route::get('/docent/rapportage/groep/{groepNaam}', [RapportageController::class, 'groepPdf'])->name('rapportage.groep.pdf');

// ➤ Profiel (alleen voor ingelogden)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ➤ Laravel auth routes
require __DIR__ . '/auth.php';
