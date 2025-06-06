<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\AanwezigheidController;


// ➤ Login functionaliteit
Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');
Route::redirect('/', '/login');

Route::get('/student-dashboard', [AanwezigheidController::class, 'index'])->name('student-dashboard');
Route::get('/individueel-student', [AanwezigheidController::class, 'individueel'])->name('individueel-student');


// ➤ Aanwezighedenoverzicht voor docenten
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index'])->name('aanwezigheden.index');

// ➤ Individuele student (bijv. vanuit docent-interface)
Route::get('/student/{studentnummer}', [AanwezigheidController::class, 'show'])->name('student.show');

// ➤ Overige pagina's
Route::view('/importing', 'importing')->name('importing');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// ➤ Excel upload
Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

// ➤ Docent-dashboard met groepsstatistieken
Route::get('/docent/dashboard', [AanwezigheidController::class, 'getDocentDashboardData'])->name('docent.dashboard');

// ➤ Route om student te markeren als gestopt
Route::post('/docent/student/{studentnummer}/stop', [AanwezigheidController::class, 'stopStudying'])->name('docent.student.stop');

// ➤ Route om alle studenten op te halen voor het logboek
Route::get('/docent/students/all', [AanwezigheidController::class, 'getAllStudentData'])->name('docent.students.all');

require __DIR__ . '/auth.php';


