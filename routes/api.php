<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AanwezigheidController;

Route::get('/aanwezigheden', [AanwezigheidController::class, 'index']);
    