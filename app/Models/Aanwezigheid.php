<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aanwezigheid extends Model
{
    use HasFactory;

    // Tabelnaam expliciet instellen (alleen nodig als de naam afwijkt van conventie)
    protected $table = 'aanwezigheden';

    // Velden die massaal ingevuld mogen worden (bijv. bij create())
    protected $fillable = [
        'studentnummer',
        'aanwezigheid',
        'rooster',
        'week',
        'jaar',
    ];

}
