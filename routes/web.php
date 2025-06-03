<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AanwezigheidController;
use App\Models\Aanwezigheid;

Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);

Route::get('/', function () {
    // Haal het eerste studentnummer op uit de database
    $eerste = Aanwezigheid::first();
    $studentnummer = $eerste?->studentnummer ?? 'Geen student gevonden';

    // Geef het door aan de dashboard-view
    return view('dashboard', compact('studentnummer'));
})->name('dashboard');

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

require __DIR__.'/auth.php';
