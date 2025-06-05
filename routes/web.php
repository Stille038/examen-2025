<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Aanwezigheid;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\AanwezigheidController; 

Route::get('/student-dashboard', [AanwezigheidController::class, 'index'])->name('student-dashboard');
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);


Route::get('/', function () {
    $studenten = Aanwezigheid::all(); 
    return view('dashboard', compact('studenten'));
})->name('dashboard');

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

require __DIR__.'/auth.php';
