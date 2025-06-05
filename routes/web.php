
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\CustomLoginController;
use App\Models\Aanwezigheid;
use App\Http\Controllers\AanwezigheidController; 

Route::get('/student-dashboard', [AanwezigheidController::class, 'index'])->name('student-dashboard');
Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);

Route::get('/', function () {
    $studenten = Aanwezigheid::all();
    return view('dashboard', compact('studenten'));
})->name('dashboard');

Route::post('/custom-login', [CustomLoginController::class, 'login'])->name('custom.login');

Route::middleware('checkStudent')->group(function () {
    Route::get('/student-dashboard', function () {
        $studentnummer = session('studentnummer');
        $studentenData = \App\Models\Aanwezigheid::where('studentnummer', $studentnummer)->get();
        return view('student-dashboard', compact('studentenData'));
    })->name('student-dashboard');

    Route::view('/individueel-student', 'individueel-student')->name('individueel-student');
});

Route::get('/aanwezigheden', [AanwezigheidController::class, 'index'])->name('aanwezigheden.index');

Route::get('/student/{studentnummer}', [AanwezigheidController::class, 'show'])->name('student.show');

Route::view('/test1', 'test1')->name('test1');
Route::view('/test2', 'test2')->name('test2');
Route::view('/test', 'test')->name('test');
Route::view('/docent/dashboard', 'docent_dashboard')->name('docent.dashboard');
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

Route::post('/upload-excel', [ExcelUploadController::class, 'store'])->name('excel.upload');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';