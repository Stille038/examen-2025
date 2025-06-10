<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aanwezigheid extends Model
{
    protected $table = 'aanwezigheden';

    protected $fillable = [
        'studentnummer',
        'aanwezigheid',
        'rooster',
        'week',
        'jaar',
        'percentage',
        'categorie',
        'kleur',
    ];
   
    public static function boot()
    {
        parent::boot();

        static::saving(function ($aanwezigheid) {
            if ($aanwezigheid->rooster > 0) {
                $percentage = ($aanwezigheid->aanwezigheid / $aanwezigheid->rooster) * 100;
            } else {
                $percentage = 0;
            }

            $aanwezigheid->percentage = round($percentage, 0);

            if ($aanwezigheid->percentage >= 100) {
                $aanwezigheid->categorie = 'Perfect';
                $aanwezigheid->kleur = 'purple';
            } elseif ($aanwezigheid->percentage >= 95) {
                $aanwezigheid->categorie = 'Excellent';
                $aanwezigheid->kleur = 'blue';
            } elseif ($aanwezigheid->percentage >= 80) {
                $aanwezigheid->categorie = 'Goed';
                $aanwezigheid->kleur = 'green';
            } elseif ($aanwezigheid->percentage >= 65) {
                $aanwezigheid->categorie = 'Redelijk';
                $aanwezigheid->kleur = 'yellow';
            } elseif ($aanwezigheid->percentage >= 50) {
                $aanwezigheid->categorie = 'Onvoldoende';
                $aanwezigheid->kleur = 'orange';
            } elseif ($aanwezigheid->percentage > 0 && $aanwezigheid->percentage < 50) {
                $aanwezigheid->categorie = 'Kritiek';
                $aanwezigheid->kleur = 'red';
            } else {
                $aanwezigheid->categorie = 'fail';
                $aanwezigheid->kleur = 'gray';
            }
        });
    }
}