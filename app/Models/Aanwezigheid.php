<?php

namespace App\Models; //geeft aan waar bestand zich bevindt binnen laravel -app

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // maakt mogelijk om met database te praten 

class Aanwezigheid extends Model // maak nieuwe model aan met naam Aanwezigheid die zorgt ervoor dat code en db kunnen praten
{
    use HasFactory;

    // Tabelnaam expliciet instellen (alleen nodig als de naam afwijkt van conventie)
    protected $table = 'aanwezigheden';

    // Velden die  ingevuld mogen worden 
    protected $fillable = [
        'studentnummer',
        'aanwezigheid',
        'rooster',
        'week',
        'jaar',
        'groep',
    ];

}
