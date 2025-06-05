<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\AanwezigheidController;

// ➤ Login functionaliteit
Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');
Route::redirect('/', '/login');

// ➤ Student dashboard (data via controller, NIET via closure)
Route::get('/student-dashboard', [AanwezigheidController::class, 'index'])->name('student-dashboard');

// ➤ Individueel studentenscherm (alleen als je iets extra's wilt)
Route::view('/individueel-student', 'individueel-student')->name('individueel-student');

// ➤ Aanwezighedenoverzicht voor docenten
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index'])->name('aanwezigheden.index');

// ➤ Individuele student (bijv. vanuit docent-interface)
Route::get('/student/{studentnummer}', [AanwezigheidController::class, 'show'])->name('student.show');

// ➤ Overige pagina's
Route::view('/test1', 'test1')->name('test1');
Route::view('/test2', 'test2')->name('test2');
Route::view('/test', 'test')->name('test');
Route::view('/docent/dashboard', 'docent_dashboard')->name('docent.dashboard');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// ➤ Excel upload
Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

// ➤ Laravel-authentificatie
require __DIR__ . '/auth.php';
