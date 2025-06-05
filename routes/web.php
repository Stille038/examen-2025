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

// ➤ Student dashboard — alleen toegankelijk als student is ingelogd
Route::get('/student-dashboard', function () {
    if (!session()->has('studentnummer')) {
        return redirect('/login');
    }

    $studentnummer = session('studentnummer');
    $studentenData = Aanwezigheid::where('studentnummer', $studentnummer)->get();
    return view('student-dashboard', compact('studentenData'));
})->name('student-dashboard');

// ➤ Alleen voor ingelogde studenten
Route::get('/individueel-student', function () {
    if (!session()->has('studentnummer')) {
        return redirect('/login');
    }
    return view('individueel-student');
})->name('individueel-student');

// ➤ Route voor alle berekende statistieken (alleen voor docent)
Route::get('/aanwezigheden', function () {
    if (!session()->has('docentnummer')) {
        return redirect('/login');
    }
    return app(AanwezigheidController::class)->index();
})->name('aanwezigheden.index');

// ➤ Individuele student detailpagina (alleen voor docent)
Route::get('/student/{studentnummer}', function ($studentnummer) {
    if (!session()->has('docentnummer')) {
        return redirect('/login');
    }
    return app(AanwezigheidController::class)->show($studentnummer);
})->name('student.show');

// ➤ Losse pagina's (voor iedereen)
Route::view('/test1', 'test1')->name('test1');
Route::view('/test2', 'test2')->name('test2');
Route::view('/test', 'test')->name('test');

// ➤ Docent dashboard — alleen toegankelijk als docent is ingelogd
Route::get('/docent/dashboard', function () {
    if (!session()->has('docentnummer')) {
        return redirect('/login');
    }

    $studenten = Aanwezigheid::all();

    $groepen = $studenten->groupBy('klas')->map(function ($groep, $naam) {
        $gemiddelde = $groep->avg(function ($s) {
            return $s->rooster ? ($s->aanwezigheid / $s->rooster) * 100 : 0;
        });

        return [
            'naam' => $naam,
            'gemiddelde' => round($gemiddelde, 0),
            'aantal' => $groep->count(),
        ];
    })->values()->all();

    return view('docent_dashboard', compact('studenten', 'groepen'));
})->name('docent.dashboard');

// ➤ Uitloggen (sessie leegmaken)
Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
})->name('logout');

// ➤ Openbare pagina’s
Route::view('/contact', 'contact')->name('contact');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');

// ➤ Excel upload — alleen voor docent
Route::post('/upload-excel', function () {
    if (!session()->has('docentnummer')) {
        return redirect('/login');
    }
    return app(ExcelUploadController::class)->store(request());
})->name('excel.upload');

// ➤ Laravel standaard auth
require __DIR__ . '/auth.php';
